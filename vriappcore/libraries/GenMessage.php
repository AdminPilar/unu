<?php defined('BASEPATH') OR exit('No direct script access allowed');
    require ('./absmain/xsms/autoload.php');
    use SMSGatewayMe\Client\ApiClient;
    use SMSGatewayMe\Client\Configuration;
    use SMSGatewayMe\Client\Api\MessageApi;
    use SMSGatewayMe\Client\Model\SendMessageRequest;
    
    class tio extends CI_Controller{
        public function __construct(){
            parent::__construct();
        }
        public function sendMsj($celu,$tipo,$codProy){
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUyNzc5MjE2OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjUzMDUwLCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.h1jyrrxvkl5RzCgCQdB5DZQz7nP0YfvX4sudy6fCvPw');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            if($tipo==1)$mensaje="UNA VRI PILAR \nSeñor docente se le hace recuerdo que deberá de ingresar a la plataforma PILAR, la tesis de Código : $codProy necesita su revisión.\nPuede acceder en http://vriunap.pe/pilar \n".date("d-m-Y");
            if($tipo==2)$mensaje="UNA VRI PILAR \nSeñor docente la tesis de Código: $codProy requiere de su DICTAMEN URGENTE, ingrese a la plataforma PILAR..\nDirección web : http://vriunap.pe/pilar \n".date("d-m-Y");
            $sendMessages = $messageClient->sendMessages([   
                new SendMessageRequest([
                    'phoneNumber' => "$celu",
                    'message' => $mensaje,
                    'deviceId' => 92837
                ]) 
            ]);
            return $sendMessages[0]['status'];
        }        
    }
?>