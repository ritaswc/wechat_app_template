<?php

namespace React\Dns\Model;

use React\Dns\Query\Query;

class Message
{
    const TYPE_A = 1;
    const TYPE_NS = 2;
    const TYPE_CNAME = 5;
    const TYPE_SOA = 6;
    const TYPE_PTR = 12;
    const TYPE_MX = 15;
    const TYPE_TXT = 16;
    const TYPE_AAAA = 28;
    const TYPE_SRV = 33;
    const TYPE_ANY = 255;

    const CLASS_IN = 1;

    const OPCODE_QUERY = 0;
    const OPCODE_IQUERY = 1; // inverse query
    const OPCODE_STATUS = 2;

    const RCODE_OK = 0;
    const RCODE_FORMAT_ERROR = 1;
    const RCODE_SERVER_FAILURE = 2;
    const RCODE_NAME_ERROR = 3;
    const RCODE_NOT_IMPLEMENTED = 4;
    const RCODE_REFUSED = 5;

    /**
     * Creates a new request message for the given query
     *
     * @param Query $query
     * @return self
     */
    public static function createRequestForQuery(Query $query)
    {
        $request = new Message();
        $request->header->set('id', self::generateId());
        $request->header->set('rd', 1);
        $request->questions[] = (array) $query;
        $request->prepare();

        return $request;
    }

    /**
     * Creates a new response message for the given query with the given answer records
     *
     * @param Query    $query
     * @param Record[] $answers
     * @return self
     */
    public static function createResponseWithAnswersForQuery(Query $query, array $answers)
    {
        $response = new Message();
        $response->header->set('id', self::generateId());
        $response->header->set('qr', 1);
        $response->header->set('opcode', Message::OPCODE_QUERY);
        $response->header->set('rd', 1);
        $response->header->set('rcode', Message::RCODE_OK);

        $response->questions[] = (array) $query;

        foreach ($answers as $record) {
            $response->answers[] = $record;
        }

        $response->prepare();

        return $response;
    }

    /**
     * generates a random 16 bit message ID
     *
     * This uses a CSPRNG so that an outside attacker that is sending spoofed
     * DNS response messages can not guess the message ID to avoid possible
     * cache poisoning attacks.
     *
     * The `random_int()` function is only available on PHP 7+ or when
     * https://github.com/paragonie/random_compat is installed. As such, using
     * the latest supported PHP version is highly recommended. This currently
     * falls back to a less secure random number generator on older PHP versions
     * in the hope that this system is properly protected against outside
     * attackers, for example by using one of the common local DNS proxy stubs.
     *
     * @return int
     * @see self::getId()
     * @codeCoverageIgnore
     */
    private static function generateId()
    {
        if (function_exists('random_int')) {
            return random_int(0, 0xffff);
        }
        return mt_rand(0, 0xffff);
    }

    public $header;
    public $questions = array();
    public $answers = array();
    public $authority = array();
    public $additional = array();

    /**
     * @deprecated still used internally for BC reasons, should not be used externally.
     */
    public $data = '';

    /**
     * @deprecated still used internally for BC reasons, should not be used externally.
     */
    public $consumed = 0;

    public function __construct()
    {
        $this->header = new HeaderBag();
    }

    /**
     * Returns the 16 bit message ID
     *
     * The response message ID has to match the request message ID. This allows
     * the receiver to verify this is the correct response message. An outside
     * attacker may try to inject fake responses by "guessing" the message ID,
     * so this should use a proper CSPRNG to avoid possible cache poisoning.
     *
     * @return int
     * @see self::generateId()
     */
    public function getId()
    {
        return $this->header->get('id');
    }

    /**
     * Returns the response code (RCODE)
     *
     * @return int see self::RCODE_* constants
     */
    public function getResponseCode()
    {
        return $this->header->get('rcode');
    }

    public function prepare()
    {
        $this->header->populateCounts($this);
    }
}
