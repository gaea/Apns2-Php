<?php


class Payload {

	private $alert;
	private $badge;
	private $sound;
	private $customData;

	public function __construct() {
        $this->sound = "default";
        $this->badge = 0;
        $this->customData = [];
    }

    public function setAlert($alert) {
        $this->alert = $alert;
    }

    public function setBadge($badge) {
        $this->badge = $badge;
    }

    public function setSound($sound) {
        $this->sound = $sound;
    }

    public function addCustomData($key, $value) {
    	$this->customData[$key] = $value;
    }

    public function toJson() {
    	$data = array(
    		"aps" => array(
    			"alert" => $this->alert,
    			"sound" => $this->sound,
    			"badge" => $this->badge
    		)
    	);

    	foreach ($this->customData as $key => $value) {
    		$data[$key] = $value;
    	}

    	return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
 
