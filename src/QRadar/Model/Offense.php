<?php

namespace QRadar\Model;

/**
 * Model class for the data returned by the QRadar Siem/Offenses endpoint.
 *
 * @property int id
 * @property string description
 * @property string assigned_to
 * @property array categories
 * @property int category_count
 * @property int policy_category_count
 * @property int security_category_count
 * @property int close_time
 * @property string closing_user
 * @property int closing_reason_id
 * @property int credibility
 * @property int relevance
 * @property int severity
 * @property int magnitude
 * @property array destination_networks
 * @property string source_network
 * @property int device_count
 * @property int event_count
 * @property int flow_count
 * @property bool inactive
 * @property int last_udpated_time
 * @property int local_destination_count
 * @property string offense_source
 * @property int offense_type
 * @property bool protected
 * @property bool follow_up
 * @property int remote_destination_count
 * @property int source_count
 * @property int start_time
 * @property string status
 * @property int username_count
*/
class Offense {

    private $raw;

    /**
     * @ignore
     */
    protected $validAttributes = array(
        'id',
        'description',
        'assigned_to',
        'categories',
        'category_count',
        'policy_category_count',
        'security_category_count',
        'close_time',
        'closing_user',
        'closing_reason_id',
        'credibility',
        'relevance',
        'severity',
        'magnitude',
        'destination_networks',
        'source_network',
        'device_count',
        'event_count',
        'flow_count',
        'inactive',
        'last_udpated_time',
        'local_destination_count',
        'offense_source',
        'offense_type',
        'protected',
        'follow_up',
        'remote_destination_count',
        'source_count',
        'start_time',
        'status',
        'username_count'
    );
    
    /**
     * @ignore
     */
    public function __construct($raw) {
        $this->raw = isset($raw) ? $raw : array();
    }

    /**
     * @ignore
     */
    public function __get($attr) {        
        $key = $this->attributeToKey($attr);

        if ($this->__isset($attr)) {
            return $this->raw[$key];
        } elseif ($this->validAttribute($attr)) {
            if (preg_match('/^is_/', $key)) {
                return false;
            } else {
                return null;
            }
        } else {
            throw new \RuntimeException("Unknown attribute: $attr");
        }
    }

    public function __isset($attr) {
        return $this->validAttribute($attr) && isset($this->raw[$this->attributeToKey($attr)]);
    }

    private function attributeToKey($attr) {
        return strtolower(preg_replace('/([A-Z])/', '_\1', $attr));
    }

    private function validAttribute($attr) {
        return in_array($attr, $this->validAttributes);
    }

    public function jsonSerialize() {
        return $this->raw;
    }

}