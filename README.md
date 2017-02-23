# Apns2-Php
 
## Getting Started

``` php

<?php

include_once '../src/Client.php';
include_once '../src/Notification.php';
include_once '../src/Payload.php';
include_once '../src/Response.php';


$topic = "app_bundle_id";
$certFilePath = "./apsCerDev.pem";

$deviceToken = "device_token";

$payload = new Payload();
$payload->setAlert("Alert message!");
$payload->addCustomData("data1", "data1");
$payload->addCustomData("dat2", "data2");

$notification = new Notification($payload, $deviceToken);

$apnsClient = new Client(false, $topic, $certFilePath);
$apnsClient->addNotification($notification);
$responses = $apnsClient->push();

foreach($responses as $response) {
	$response->printResponse();
}