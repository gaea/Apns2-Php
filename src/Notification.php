<?php

include_once 'Payload.php';


class Notification {

	private $id;
	private $deviceToken;
	private $payload;

	public function __construct(Payload $payload, $deviceToken) {
        $this->payload = $payload;
        $this->deviceToken = $deviceToken;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setPayload(Payload $payload) {
        $this->payload = $payload;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function setDeviceToken($deviceToken) {
        $this->deviceToken = $deviceToken;
    }

    public function getDeviceToken() {
        return $this->deviceToken;
    }

    public function getMessage() {
    	return $this->payload->toJson();
    }
}
