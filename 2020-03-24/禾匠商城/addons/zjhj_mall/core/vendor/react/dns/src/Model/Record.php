<?php

namespace React\Dns\Model;

class Record
{
    /**
     * @var string hostname without trailing dot, for example "reactphp.org"
     */
    public $name;

    /**
     * @var int see Message::TYPE_* constants (UINT16)
     */
    public $type;

    /**
     * @var int see Message::CLASS_IN constant (UINT16)
     */
    public $class;

    /**
     * @var int maximum TTL in seconds (UINT16)
     */
    public $ttl;

    /**
     * The payload data for this record
     *
     * The payload data format depends on the record type. As a rule of thumb,
     * this library will try to express this in a way that can be consumed
     * easily without having to worry about DNS internals and its binary transport:
     *
     * - A:
     *   IPv4 address string, for example "192.168.1.1".
     * - AAAA:
     *   IPv6 address string, for example "::1".
     * - CNAME / PTR / NS:
     *   The hostname without trailing dot, for example "reactphp.org".
     * - TXT:
     *   List of string values, for example `["v=spf1 include:example.com"]`.
     *   This is commonly a list with only a single string value, but this
     *   technically allows multiple strings (0-255 bytes each) in a single
     *   record. This is rarely used and depending on application you may want
     *   to join these together or handle them separately. Each string can
     *   transport any binary data, its character encoding is not defined (often
     *   ASCII/UTF-8 in practice). [RFC 1464](https://tools.ietf.org/html/rfc1464)
     *   suggests using key-value pairs such as `["name=test","version=1"]`, but
     *   interpretation of this is not enforced and left up to consumers of this
     *   library (used for DNS-SD/Zeroconf and others).
     * - MX:
     *   Mail server priority (UINT16) and target hostname without trailing dot,
     *   for example `{"priority":10,"target":"mx.example.com"}`.
     *   The payload data uses an associative array with fixed keys "priority"
     *   (also commonly referred to as weight or preference) and "target" (also
     *   referred to as exchange). If a response message contains multiple
     *   records of this type, targets should be sorted by priority (lowest
     *   first) - this is left up to consumers of this library (used for SMTP).
     * - SRV:
     *   Service priority (UINT16), service weight (UINT16), service port (UINT16)
     *   and target hostname without trailing dot, for example
     *   `{"priority":10,"weight":50,"port":8080,"target":"example.com"}`.
     *   The payload data uses an associative array with fixed keys "priority",
     *   "weight", "port" and "target" (also referred to as name).
     *   The target may be an empty host name string if the service is decidedly
     *   not available. If a response message contains multiple records of this
     *   type, targets should be sorted by priority (lowest first) and selected
     *   randomly according to their weight - this is left up to consumers of
     *   this library, see also [RFC 2782](https://tools.ietf.org/html/rfc2782)
     *   for more details.
     * - SOA:
     *   Includes master hostname without trailing dot, responsible person email
     *   as hostname without trailing dot and serial, refresh, retry, expire and
     *   minimum times in seconds (UINT32 each), for example:
     *   `{"mname":"ns.example.com","rname":"hostmaster.example.com","serial":
     *   2018082601,"refresh":3600,"retry":1800,"expire":60000,"minimum":3600}`.
     * - Any other unknown type:
     *   An opaque binary string containing the RDATA as transported in the DNS
     *   record. For forwards compatibility, you should not rely on this format
     *   for unknown types. Future versions may add support for new types and
     *   this may then parse the payload data appropriately - this will not be
     *   considered a BC break. See the format definition of known types above
     *   for more details.
     *
     * @var string|string[]|array
     */
    public $data;

    public function __construct($name, $type, $class, $ttl = 0, $data = null)
    {
        $this->name     = $name;
        $this->type     = $type;
        $this->class    = $class;
        $this->ttl      = $ttl;
        $this->data     = $data;
    }
}
