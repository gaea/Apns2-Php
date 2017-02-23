<?php


class Response {

	private $statusCode;
	private $devicetoken;
	private $protocol;
	private $apnsId;
	private $message;
	private $body;
	private $error;

	public function __construct() {
		
	}

	public function setStatus($statusCode) {
		$this->statusCode = $statusCode;
	}

	public function getStatus() {
		return $this->statusCode;
	}

	public function setDeviceToken($devicetoken) {
		$this->devicetoken = $devicetoken;
	}

	public function getDeviceToken() {
		return $this->devicetoken;
	}
	
	public function setApnsId($apnsId) {
		$this->apnsId = $apnsId;
	}

	public function getApnsId() {
		return $this->apnsId;
	}
	
	public function setProtocol($protocol) {
		$this->protocol = $protocol;
	}

	public function getProtocol() {
		return $this->protocol;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setBody($body) {
		if(is_array($body)) {
			$this->error = $body["reason"];
			$this->body = "";
		}
		else {
			$this->body = $body;
		}
	}

	public function getBody() {
		return $this->body;
	}

	public function setError($error) {
		$this->error = $error;
	}

	public function getError() {
		return $this->error;
	}

	public function responseToarray() {
		return array(
			"protocol" => $this->protocol,
			"status" => $this->statusCode,
			"deviceToken" => $this->devicetoken,
			"apnsId" => $this->apnsId,
			"message" => $this->message,
			"body" => $this->body,
			"error" => $this->error
		);
	}

	public function printResponse() {
		print_r($this->responseToarray());
	}
}
