<?php

include_once 'Request.php';
include_once 'Notification.php';
include_once 'Response.php';


class Client {

	private $notifications;
	private $requestManager;

	public function __construct($isProductionEnv, $topic, $pemFile) {
		$this->requestManager = new Request($isProductionEnv);
		$this->requestManager->setApnsPemFile($pemFile);
		$this->requestManager->setApnsTopic($topic);

		$this->notifications = [];
	}

	public function addNotification(Notification $notification) {
		$this->notifications[] = $notification;
	}

	public function push() {
		$mh = curl_multi_init();
		curl_multi_setopt($mh, CURLMOPT_PIPELINING, CURLPIPE_MULTIPLEX);
		
		$handles = [];

		foreach ($this->notifications as $k => $notification) {
			$deviceToken = $notification->getDeviceToken();
			$message = $notification->getMessage();

			$ch = $this->requestManager->createNotificationRequest($notification->getId(), $deviceToken, $message);

			$handle = array(
				"index" => $k,
				"devicetoken" => $deviceToken,
				"request" => $ch,
				"message" => $message
			);

			$handles[] = $handle;

			curl_multi_add_handle($mh, $ch);
		}

		do {
			$mrc = curl_multi_exec($mh, $active);
		} while ($active > 0);

		$responses = $this->manageResponse($mh, $handles);

		curl_multi_close($mh);

		return $responses;
	}

	private function manageResponse($mh, $handles) {
		$responseCollection = [];

		foreach ($handles as $handle) {
			$statusCode = curl_getinfo($handle["request"], CURLINFO_HTTP_CODE);
			$result = curl_multi_getcontent($handle["request"]);
			
			curl_multi_remove_handle($mh, $handle["request"]);
			list($headers, $body) = explode("\r\n\r\n", $result, 2);
			
			$body = json_decode($body, true);
			$headers = explode(" ", $headers);

			$response = new Response();
			$response->setStatus($statusCode);
			$response->setDeviceToken($handle["devicetoken"]);
			$response->setApnsId($headers[3]);
			$response->setProtocol($headers[0]);
			$response->setMessage($handle["message"]);
			$response->setBody($body);
			
			$responseCollection[] = $response;
		}

		return $responseCollection;
	}
}