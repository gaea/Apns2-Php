<?php


class Request {
	
	private $apnsPort = 443;
	private $apnsPdnServer = "https://api.push.apple.com";
	private $apnsDevServer = "https://api.development.push.apple.com";
	private $apnsService = "/3/device/";
	
	private $apnsServer;
	private $pemFile;
	private $apnsTopic;

	private $headers = [];
	private $enableVerbose = false;
	
	public function __construct($isProductionEnv = false) {
		$this->apnsServer = $isProductionEnv ? $this->apnsPdnServer : $this->apnsDevServer;
	}

	public function addHeader($key, $value) {
		$this->headers[$key] = $value;
	}

	private function buildHeaders() {
		$requestHeader = [];

		foreach ($this->headers as $name => $value) {
            $requestHeader[] = $name . ': ' . $value;
        }

        return $requestHeader;
	}

	public function enableVerbose() {
		$this->enableVerbose = true;
	}
	
	public function createNotificationRequest($id, $token, $message) {
		if(!empty($id)) {
			$this->addHeader("apns-id", $id);
		}

		$this->addHeader("apns-topic", $this->apnsTopic);

		$ch = curl_init($this->buildUrl($token));
		
		curl_setopt($ch, CURLOPT_VERBOSE, $this->enableVerbose);
		curl_setopt($ch, CURLOPT_PORT, $this->apnsPort);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
		curl_setopt($ch, CURLOPT_HTTPHEADER,  $this->buildHeaders());
		curl_setopt($ch, CURLOPT_SSLCERT, $this->pemFile);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		
		return $ch;
	}

	private function buildUrl($token) {
		return $this->apnsServer.$this->apnsService.$token;
	}

	public function setApnsPemFile($pemFile) {
		$this->pemFile = $pemFile;
	}

	public function setApnsTopic($apnsTopic) {
		$this->apnsTopic = $apnsTopic;
	}
}
