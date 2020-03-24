<?php

namespace React\Dns\Protocol;

use React\Dns\Model\Message;
use React\Dns\Model\Record;
use InvalidArgumentException;

/**
 * DNS protocol parser
 *
 * Obsolete and uncommon types and classes are not implemented.
 */
class Parser
{
    /**
     * Parses the given raw binary message into a Message object
     *
     * @param string $data
     * @throws InvalidArgumentException
     * @return Message
     */
    public function parseMessage($data)
    {
        $message = new Message();
        if ($this->parse($data, $message) !== $message) {
            throw new InvalidArgumentException('Unable to parse binary message');
        }

        return $message;
    }

    /**
     * @deprecated unused, exists for BC only
     */
    public function parseChunk($data, Message $message)
    {
        return $this->parse($data, $message);
    }

    private function parse($data, Message $message)
    {
        $message->data .= $data;

        if (!$message->header->get('id')) {
            if (!$this->parseHeader($message)) {
                return;
            }
        }

        if ($message->header->get('qdCount') != count($message->questions)) {
            if (!$this->parseQuestion($message)) {
                return;
            }
        }

        if ($message->header->get('anCount') != count($message->answers)) {
            if (!$this->parseAnswer($message)) {
                return;
            }
        }

        return $message;
    }

    public function parseHeader(Message $message)
    {
        if (strlen($message->data) < 12) {
            return;
        }

        $header = substr($message->data, 0, 12);
        $message->consumed += 12;

        list($id, $fields, $qdCount, $anCount, $nsCount, $arCount) = array_values(unpack('n*', $header));

        $rcode = $fields & bindec('1111');
        $z = ($fields >> 4) & bindec('111');
        $ra = ($fields >> 7) & 1;
        $rd = ($fields >> 8) & 1;
        $tc = ($fields >> 9) & 1;
        $aa = ($fields >> 10) & 1;
        $opcode = ($fields >> 11) & bindec('1111');
        $qr = ($fields >> 15) & 1;

        $vars = compact('id', 'qdCount', 'anCount', 'nsCount', 'arCount',
                            'qr', 'opcode', 'aa', 'tc', 'rd', 'ra', 'z', 'rcode');


        foreach ($vars as $name => $value) {
            $message->header->set($name, $value);
        }

        return $message;
    }

    public function parseQuestion(Message $message)
    {
        if (strlen($message->data) < 2) {
            return;
        }

        $consumed = $message->consumed;

        list($labels, $consumed) = $this->readLabels($message->data, $consumed);

        if (null === $labels) {
            return;
        }

        if (strlen($message->data) - $consumed < 4) {
            return;
        }

        list($type, $class) = array_values(unpack('n*', substr($message->data, $consumed, 4)));
        $consumed += 4;

        $message->consumed = $consumed;

        $message->questions[] = array(
            'name' => implode('.', $labels),
            'type' => $type,
            'class' => $class,
        );

        if ($message->header->get('qdCount') != count($message->questions)) {
            return $this->parseQuestion($message);
        }

        return $message;
    }

    public function parseAnswer(Message $message)
    {
        if (strlen($message->data) < 2) {
            return;
        }

        $consumed = $message->consumed;

        list($labels, $consumed) = $this->readLabels($message->data, $consumed);

        if (null === $labels) {
            return;
        }

        if (strlen($message->data) - $consumed < 10) {
            return;
        }

        list($type, $class) = array_values(unpack('n*', substr($message->data, $consumed, 4)));
        $consumed += 4;

        list($ttl) = array_values(unpack('N', substr($message->data, $consumed, 4)));
        $consumed += 4;

        list($rdLength) = array_values(unpack('n', substr($message->data, $consumed, 2)));
        $consumed += 2;

        $rdata = null;

        if (Message::TYPE_A === $type || Message::TYPE_AAAA === $type) {
            $ip = substr($message->data, $consumed, $rdLength);
            $consumed += $rdLength;

            $rdata = inet_ntop($ip);
        } elseif (Message::TYPE_CNAME === $type || Message::TYPE_PTR === $type || Message::TYPE_NS === $type) {
            list($bodyLabels, $consumed) = $this->readLabels($message->data, $consumed);

            $rdata = implode('.', $bodyLabels);
        } elseif (Message::TYPE_TXT === $type) {
            $rdata = array();
            $remaining = $rdLength;
            while ($remaining) {
                $len = ord($message->data[$consumed]);
                $rdata[] = substr($message->data, $consumed + 1, $len);
                $consumed += $len + 1;
                $remaining -= $len + 1;
            }
        } elseif (Message::TYPE_MX === $type) {
            list($priority) = array_values(unpack('n', substr($message->data, $consumed, 2)));
            list($bodyLabels, $consumed) = $this->readLabels($message->data, $consumed + 2);

            $rdata = array(
                'priority' => $priority,
                'target' => implode('.', $bodyLabels)
            );
        } elseif (Message::TYPE_SRV === $type) {
            list($priority, $weight, $port) = array_values(unpack('n*', substr($message->data, $consumed, 6)));
            list($bodyLabels, $consumed) = $this->readLabels($message->data, $consumed + 6);

            $rdata = array(
                'priority' => $priority,
                'weight' => $weight,
                'port' => $port,
                'target' => implode('.', $bodyLabels)
            );
        } elseif (Message::TYPE_SOA === $type) {
            list($primaryLabels, $consumed) = $this->readLabels($message->data, $consumed);
            list($mailLabels, $consumed) = $this->readLabels($message->data, $consumed);
            list($serial, $refresh, $retry, $expire, $minimum) = array_values(unpack('N*', substr($message->data, $consumed, 20)));
            $consumed += 20;

            $rdata = array(
                'mname' => implode('.', $primaryLabels),
                'rname' => implode('.', $mailLabels),
                'serial' => $serial,
                'refresh' => $refresh,
                'retry' => $retry,
                'expire' => $expire,
                'minimum' => $minimum
            );
        } else {
            // unknown types simply parse rdata as an opaque binary string
            $rdata = substr($message->data, $consumed, $rdLength);
            $consumed += $rdLength;
        }

        $message->consumed = $consumed;

        $name = implode('.', $labels);
        $ttl = $this->signedLongToUnsignedLong($ttl);
        $record = new Record($name, $type, $class, $ttl, $rdata);

        $message->answers[] = $record;

        if ($message->header->get('anCount') != count($message->answers)) {
            return $this->parseAnswer($message);
        }

        return $message;
    }

    private function readLabels($data, $consumed)
    {
        $labels = array();

        while (true) {
            if ($this->isEndOfLabels($data, $consumed)) {
                $consumed += 1;
                break;
            }

            if ($this->isCompressedLabel($data, $consumed)) {
                list($newLabels, $consumed) = $this->getCompressedLabel($data, $consumed);
                $labels = array_merge($labels, $newLabels);
                break;
            }

            $length = ord(substr($data, $consumed, 1));
            $consumed += 1;

            if (strlen($data) - $consumed < $length) {
                return array(null, null);
            }

            $labels[] = substr($data, $consumed, $length);
            $consumed += $length;
        }

        return array($labels, $consumed);
    }

    public function isEndOfLabels($data, $consumed)
    {
        $length = ord(substr($data, $consumed, 1));
        return 0 === $length;
    }

    public function getCompressedLabel($data, $consumed)
    {
        list($nameOffset, $consumed) = $this->getCompressedLabelOffset($data, $consumed);
        list($labels) = $this->readLabels($data, $nameOffset);

        return array($labels, $consumed);
    }

    public function isCompressedLabel($data, $consumed)
    {
        $mask = 0xc000; // 1100000000000000
        list($peek) = array_values(unpack('n', substr($data, $consumed, 2)));

        return (bool) ($peek & $mask);
    }

    public function getCompressedLabelOffset($data, $consumed)
    {
        $mask = 0x3fff; // 0011111111111111
        list($peek) = array_values(unpack('n', substr($data, $consumed, 2)));

        return array($peek & $mask, $consumed + 2);
    }

    public function signedLongToUnsignedLong($i)
    {
        return $i & 0x80000000 ? $i - 0xffffffff : $i;
    }
}
