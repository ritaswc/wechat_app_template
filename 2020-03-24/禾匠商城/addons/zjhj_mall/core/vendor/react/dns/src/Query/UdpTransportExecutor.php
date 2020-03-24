<?php

namespace React\Dns\Query;

use React\Dns\Model\Message;
use React\Dns\Protocol\BinaryDumper;
use React\Dns\Protocol\Parser;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;

/**
 * Send DNS queries over a UDP transport.
 *
 * This is the main class that sends a DNS query to your DNS server and is used
 * internally by the `Resolver` for the actual message transport.
 *
 * For more advanced usages one can utilize this class directly.
 * The following example looks up the `IPv6` address for `igor.io`.
 *
 * ```php
 * $loop = Factory::create();
 * $executor = new UdpTransportExecutor($loop);
 *
 * $executor->query(
 *     '8.8.8.8:53',
 *     new Query($name, Message::TYPE_AAAA, Message::CLASS_IN)
 * )->then(function (Message $message) {
 *     foreach ($message->answers as $answer) {
 *         echo 'IPv6: ' . $answer->data . PHP_EOL;
 *     }
 * }, 'printf');
 *
 * $loop->run();
 * ```
 *
 * See also the [fourth example](examples).
 *
 * Note that this executor does not implement a timeout, so you will very likely
 * want to use this in combination with a `TimeoutExecutor` like this:
 *
 * ```php
 * $executor = new TimeoutExecutor(
 *     new UdpTransportExecutor($loop),
 *     3.0,
 *     $loop
 * );
 * ```
 *
 * Also note that this executor uses an unreliable UDP transport and that it
 * does not implement any retry logic, so you will likely want to use this in
 * combination with a `RetryExecutor` like this:
 *
 * ```php
 * $executor = new RetryExecutor(
 *     new TimeoutExecutor(
 *         new UdpTransportExecutor($loop),
 *         3.0,
 *         $loop
 *     )
 * );
 * ```
 *
 * > Internally, this class uses PHP's UDP sockets and does not take advantage
 *   of [react/datagram](https://github.com/reactphp/datagram) purely for
 *   organizational reasons to avoid a cyclic dependency between the two
 *   packages. Higher-level components should take advantage of the Datagram
 *   component instead of reimplementing this socket logic from scratch.
 */
class UdpTransportExecutor implements ExecutorInterface
{
    private $loop;
    private $parser;
    private $dumper;

    /**
     * @param LoopInterface     $loop
     * @param null|Parser       $parser optional/advanced: DNS protocol parser to use
     * @param null|BinaryDumper $dumper optional/advanced: DNS protocol dumper to use
     */
    public function __construct(LoopInterface $loop, Parser $parser = null, BinaryDumper $dumper = null)
    {
        if ($parser === null) {
            $parser = new Parser();
        }
        if ($dumper === null) {
            $dumper = new BinaryDumper();
        }

        $this->loop = $loop;
        $this->parser = $parser;
        $this->dumper = $dumper;
    }

    public function query($nameserver, Query $query)
    {
        $request = Message::createRequestForQuery($query);

        $queryData = $this->dumper->toBinary($request);
        if (isset($queryData[512])) {
            return \React\Promise\reject(new \RuntimeException(
                'DNS query for ' . $query->name . ' failed: Query too large for UDP transport'
            ));
        }

        // UDP connections are instant, so try connection without a loop or timeout
        $socket = @\stream_socket_client("udp://$nameserver", $errno, $errstr, 0);
        if ($socket === false) {
            return \React\Promise\reject(new \RuntimeException(
                'DNS query for ' . $query->name . ' failed: Unable to connect to DNS server ('  . $errstr . ')',
                $errno
            ));
        }

        // set socket to non-blocking and immediately try to send (fill write buffer)
        \stream_set_blocking($socket, false);
        \fwrite($socket, $queryData);

        $loop = $this->loop;
        $deferred = new Deferred(function () use ($loop, $socket, $query) {
            // cancellation should remove socket from loop and close socket
            $loop->removeReadStream($socket);
            \fclose($socket);

            throw new CancellationException('DNS query for ' . $query->name . ' has been cancelled');
        });

        $parser = $this->parser;
        $loop->addReadStream($socket, function ($socket) use ($loop, $deferred, $query, $parser, $request) {
            // try to read a single data packet from the DNS server
            // ignoring any errors, this is uses UDP packets and not a stream of data
            $data = @\fread($socket, 512);

            try {
                $response = $parser->parseMessage($data);
            } catch (\Exception $e) {
                // ignore and await next if we received an invalid message from remote server
                // this may as well be a fake response from an attacker (possible DOS)
                return;
            }

            // ignore and await next if we received an unexpected response ID
            // this may as well be a fake response from an attacker (possible cache poisoning)
            if ($response->getId() !== $request->getId()) {
                return;
            }

            // we only react to the first valid message, so remove socket from loop and close
            $loop->removeReadStream($socket);
            \fclose($socket);

            if ($response->header->isTruncated()) {
                $deferred->reject(new \RuntimeException('DNS query for ' . $query->name . ' failed: The server returned a truncated result for a UDP query, but retrying via TCP is currently not supported'));
                return;
            }

            $deferred->resolve($response);
        });

        return $deferred->promise();
    }
}
