<?php

namespace React\Dns\Resolver;

use React\Dns\Model\Message;
use React\Dns\Query\ExecutorInterface;
use React\Dns\Query\Query;
use React\Dns\RecordNotFoundException;
use React\Promise\PromiseInterface;

class Resolver
{
    private $nameserver;
    private $executor;

    public function __construct($nameserver, ExecutorInterface $executor)
    {
        $this->nameserver = $nameserver;
        $this->executor = $executor;
    }

    /**
     * Resolves the given $domain name to a single IPv4 address (type `A` query).
     *
     * ```php
     * $resolver->resolve('reactphp.org')->then(function ($ip) {
     *     echo 'IP for reactphp.org is ' . $ip . PHP_EOL;
     * });
     * ```
     *
     * This is one of the main methods in this package. It sends a DNS query
     * for the given $domain name to your DNS server and returns a single IP
     * address on success.
     *
     * If the DNS server sends a DNS response message that contains more than
     * one IP address for this query, it will randomly pick one of the IP
     * addresses from the response. If you want the full list of IP addresses
     * or want to send a different type of query, you should use the
     * [`resolveAll()`](#resolveall) method instead.
     *
     * If the DNS server sends a DNS response message that indicates an error
     * code, this method will reject with a `RecordNotFoundException`. Its
     * message and code can be used to check for the response code.
     *
     * If the DNS communication fails and the server does not respond with a
     * valid response message, this message will reject with an `Exception`.
     *
     * Pending DNS queries can be cancelled by cancelling its pending promise like so:
     *
     * ```php
     * $promise = $resolver->resolve('reactphp.org');
     *
     * $promise->cancel();
     * ```
     *
     * @param string $domain
     * @return PromiseInterface Returns a promise which resolves with a single IP address on success or
     *     rejects with an Exception on error.
     */
    public function resolve($domain)
    {
        return $this->resolveAll($domain, Message::TYPE_A)->then(function (array $ips) {
            return $ips[array_rand($ips)];
        });
    }

    /**
     * Resolves all record values for the given $domain name and query $type.
     *
     * ```php
     * $resolver->resolveAll('reactphp.org', Message::TYPE_A)->then(function ($ips) {
     *     echo 'IPv4 addresses for reactphp.org ' . implode(', ', $ips) . PHP_EOL;
     * });
     *
     * $resolver->resolveAll('reactphp.org', Message::TYPE_AAAA)->then(function ($ips) {
     *     echo 'IPv6 addresses for reactphp.org ' . implode(', ', $ips) . PHP_EOL;
     * });
     * ```
     *
     * This is one of the main methods in this package. It sends a DNS query
     * for the given $domain name to your DNS server and returns a list with all
     * record values on success.
     *
     * If the DNS server sends a DNS response message that contains one or more
     * records for this query, it will return a list with all record values
     * from the response. You can use the `Message::TYPE_*` constants to control
     * which type of query will be sent. Note that this method always returns a
     * list of record values, but each record value type depends on the query
     * type. For example, it returns the IPv4 addresses for type `A` queries,
     * the IPv6 addresses for type `AAAA` queries, the hostname for type `NS`,
     * `CNAME` and `PTR` queries and structured data for other queries. See also
     * the `Record` documentation for more details.
     *
     * If the DNS server sends a DNS response message that indicates an error
     * code, this method will reject with a `RecordNotFoundException`. Its
     * message and code can be used to check for the response code.
     *
     * If the DNS communication fails and the server does not respond with a
     * valid response message, this message will reject with an `Exception`.
     *
     * Pending DNS queries can be cancelled by cancelling its pending promise like so:
     *
     * ```php
     * $promise = $resolver->resolveAll('reactphp.org', Message::TYPE_AAAA);
     *
     * $promise->cancel();
     * ```
     *
     * @param string $domain
     * @return PromiseInterface Returns a promise which resolves with all record values on success or
     *     rejects with an Exception on error.
     */
    public function resolveAll($domain, $type)
    {
        $query = new Query($domain, $type, Message::CLASS_IN);
        $that = $this;

        return $this->executor->query(
            $this->nameserver,
            $query
        )->then(function (Message $response) use ($query, $that) {
            return $that->extractValues($query, $response);
        });
    }

    /**
     * @deprecated unused, exists for BC only
     */
    public function extractAddress(Query $query, Message $response)
    {
        $addresses = $this->extractValues($query, $response);

        return $addresses[array_rand($addresses)];
    }

    /**
     * [Internal] extract all resource record values from response for this query
     *
     * @param Query   $query
     * @param Message $response
     * @return array
     * @throws RecordNotFoundException when response indicates an error or contains no data
     * @internal
     */
    public function extractValues(Query $query, Message $response)
    {
        // reject if response code indicates this is an error response message
        $code = $response->getResponseCode();
        if ($code !== Message::RCODE_OK) {
            switch ($code) {
                case Message::RCODE_FORMAT_ERROR:
                    $message = 'Format Error';
                    break;
                case Message::RCODE_SERVER_FAILURE:
                    $message = 'Server Failure';
                    break;
                case Message::RCODE_NAME_ERROR:
                    $message = 'Non-Existent Domain / NXDOMAIN';
                    break;
                case Message::RCODE_NOT_IMPLEMENTED:
                    $message = 'Not Implemented';
                    break;
                case Message::RCODE_REFUSED:
                    $message = 'Refused';
                    break;
                default:
                    $message = 'Unknown error response code ' . $code;
            }
            throw new RecordNotFoundException(
                'DNS query for ' . $query->name . ' returned an error response (' . $message . ')',
                $code
            );
        }

        $answers = $response->answers;
        $addresses = $this->valuesByNameAndType($answers, $query->name, $query->type);

        // reject if we did not receive a valid answer (domain is valid, but no record for this type could be found)
        if (0 === count($addresses)) {
            throw new RecordNotFoundException(
                'DNS query for ' . $query->name . ' did not return a valid answer (NOERROR / NODATA)'
            );
        }

        return array_values($addresses);
    }

    /**
     * @deprecated unused, exists for BC only
     */
    public function resolveAliases(array $answers, $name)
    {
        return $this->valuesByNameAndType($answers, $name, Message::TYPE_A);
    }

    /**
     * @param \React\Dns\Model\Record[] $answers
     * @param string                    $name
     * @param int                       $type
     * @return array
     */
    private function valuesByNameAndType(array $answers, $name, $type)
    {
        // return all record values for this name and type (if any)
        $named = $this->filterByName($answers, $name);
        $records = $this->filterByType($named, $type);
        if ($records) {
            return $this->mapRecordData($records);
        }

        // no matching records found? check if there are any matching CNAMEs instead
        $cnameRecords = $this->filterByType($named, Message::TYPE_CNAME);
        if ($cnameRecords) {
            $cnames = $this->mapRecordData($cnameRecords);
            foreach ($cnames as $cname) {
                $records = array_merge(
                    $records,
                    $this->valuesByNameAndType($answers, $cname, $type)
                );
            }
        }

        return $records;
    }

    private function filterByName(array $answers, $name)
    {
        return $this->filterByField($answers, 'name', $name);
    }

    private function filterByType(array $answers, $type)
    {
        return $this->filterByField($answers, 'type', $type);
    }

    private function filterByField(array $answers, $field, $value)
    {
        $value = strtolower($value);
        return array_filter($answers, function ($answer) use ($field, $value) {
            return $value === strtolower($answer->$field);
        });
    }

    private function mapRecordData(array $records)
    {
        return array_map(function ($record) {
            return $record->data;
        }, $records);
    }
}
