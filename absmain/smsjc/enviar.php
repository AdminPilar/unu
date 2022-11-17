<?php
include "smsGateway.php";
$smsGateway = new SmsGateway('jcesarblues@live.com', 'mapa4violeta');

$var=1;


$deviceID = 70559;
$number = '0051974548089';


$options = [
'send_at' => strtotime('+1 minutes'), // Send the message in 10 minutes
'expires_at' => strtotime('+1 hour') // Cancel the message in 1 hour if the message is not yet sent
];

//Please note options is no required and can be left out


for($i=0; $i<40 ; $i++)
{
	$message = 'Mensaje 2 '.$var;
$result=$smsGateway->sendMessageToNumber($number, $message, $deviceID);
$var=$var+1;


}


print_r($result);



?>