<?php

namespace Hejiang\Express;

use Hejiang\Express\Trackers\TrackerInterface;

/**
 * Waybill model
 *
 * @property-read Traces $traces
 */
class Waybill extends \yii\base\BaseObject implements \JsonSerializable
{
    /**
     * Unique ID of waybill
     *
     * @var string
     */
    public $id;

    /**
     * Unique ID of waybill order
     *
     * @var string
     */
    public $orderId;

    /**
     * Express company name, not ended-with `物流` / `快递` / `快运` / `速递` / `速运`
     *
     * @var string
     */
    public $express;

    /**
     * Status of waybill
     *
     * @var string|int
     */
    public $status;
    
    /**
     * Traces data
     *
     * @var Traces
     */
    protected $traces;

    public function __construct()
    {
        $this->traces = new Traces();
        parent::__construct();
    }

    public function getTraces(TrackerInterface $tracker = null)
    {
        if ($tracker != null) {
            $tracker->track($this);
        }
        return $this->traces;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
