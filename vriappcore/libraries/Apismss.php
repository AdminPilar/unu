<?php defined('BASEPATH') OR exit('No direct script access allowed');

require ('./absmain/xsms/autoload.php');
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Model\CancelMessageRequest;

class Apismss {

        static $baseUrl = "https://smsgateway.me";


        // function __construct($email,$password) {
        //     $this->email = $email;
        //     $this->password = $password;
        // }
        function __construct() {
            $this->email = 'soportepilarunu@gmail.com';
            $this->password = 'Pilar456'; 
        }

        public function delete($iddelete){
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NDU5MzU4MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkyOTY5LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DfRkYmL_y8UigphotQWDi81bRO1i3Wnr9Q-FiE76Opw');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            // Cancel SMS Message
                $cancelMessageRequest1 = new CancelMessageRequest([
                    'id' => $iddelete
                ]);
   

                $canceledMessaged = $messageClient->cancelMessages([
                    $cancelMessageRequest1
                ]);
                print_r($canceledMessaged);
        }
       
        public function sendMsj($celu,$tipo){
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NDU5MzU4MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkyOTY5LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DfRkYmL_y8UigphotQWDi81bRO1i3Wnr9Q-FiE76Opw');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            if($tipo==1)$mensaje="UNU - PILAR \nEstimado(a) Docente se le hace recuerdo que deberá de ingresar a la plataforma PILAR, ya que existen PROYECTOS que necesitan de su revisión.\nPuede acceder en http://pilar.unu.edu.pe/unu/pilar \n\n".date("d-m-Y");
            if($tipo==2)$mensaje="UNU - PILAR \nEstimado(a) docente existen PROYECTOS que requieren de su DICTAMEN URGENTE, ingrese a la plataforma PILAR..\nDirección web : http://vriunap.pe/pilar \n".date("d-m-Y");
            if($tipo==3)$mensaje="UNA VRI PILAR \nSeñor docente CONFIRME su participación en el programa LASPAU en la.\nDirección web : http://vriunap.pe/pilar \n".date("d-m-Y");
            if($tipo==4)$mensaje="UNA VRI PILAR \nPostulación CONFIRMADA.<br> Bienvenido al programa LASPAU.\nDirección web : http://vriunap.pe/pilar \n".date("d-m-Y");
            $sendMessages = $messageClient->sendMessages([   
                new SendMessageRequest([
                    'phoneNumber' => "$celu",
                    'message' => $mensaje,
                    'deviceId' => 126565
                ]) 
            ]);
            return $sendMessages[0]['status'];
        }

        public function sendFeduMSJ($celu){
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NDU5MzU4MywiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkyOTY5LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.DfRkYmL_y8UigphotQWDi81bRO1i3Wnr9Q-FiE76Opw');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);
            $mensaje="UNA VRI PILAR FEDU \nSeñor docente se le hace recuerdo que NO realizó el informe de avance de su proyecto registrado en FEDU, puede realizarlo en las próximas 12 Horas en http://vriunap.pe/fedu caso contrario no se realizará la bonificiación correspondiente.\n\n".date("d-m-Y");
            $sendMessages = $messageClient->sendMessages([   
                new SendMessageRequest([
                    'phoneNumber' => "$celu",
                    'message' => $mensaje,
                    'deviceId' => 127265
                ]) 
            ]);
            return $sendMessages[0]['status'];
        }
        function createContact ($name,$number) {
            return $this->makeRequest('/api/v3/contacts/create','POST',['name' => $name, 'number' => $number]);
        }
        function getContacts ($page=1) {
           return $this->makeRequest('/api/v3/contacts','GET',['page' => $page]);
        }

        function getContact ($id) {
            return $this->makeRequest('/api/v3/contacts/view/'.$id,'GET');
        }


        function getDevices ($page=1)
        {
            return $this->makeRequest('/api/v3/devices','GET',['page' => $page]);
        }

        function getDevice ($id)
        {
            return $this->makeRequest('/api/v3/devices/view/'.$id,'GET');
        }

        function getMessages($page=1)
        {
            return $this->makeRequest('/api/v3/messages','GET',['page' => $page]);
        }

        function getMessage($id)
        {
            return $this->makeRequest('/api/v3/messages/view/'.$id,'GET');
        }

        function sendMessageToNumber($to, $message, $device, $options=[]) {
            $query = array_merge(['number'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST',$query);
        }

        public function sendMessageToNumber2($celu,$mensaje){
            $config = Configuration::getDefaultConfiguration();
            $config->setApiKey('Authorization', 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTY0NDU5NDQxNSwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjkyOTY5LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.D3f9ZFRvL9purWI_Ox-oL8TyXwSBKJceJrR4SQeb48k');
            $apiClient = new ApiClient($config);
            $messageClient = new MessageApi($apiClient);           
            $sendMessages = $messageClient->sendMessages([   
                new SendMessageRequest([
                    'phoneNumber' => "$celu",
                    'message' => $mensaje,
                    'deviceId' => 127265
                ]) 
            ]);
            return $sendMessages[0]['status'];
        }

        function sendMessageToManyNumbers ($to, $message, $device, $options=[]) {
            $query = array_merge(['number'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendMessageToContact ($to, $message, $device, $options=[]) {
            $query = array_merge(['contact'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendMessageToManyContacts ($to, $message, $device, $options=[]) {
            $query = array_merge(['contact'=>$to, 'message'=>$message, 'device' => $device], $options);
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        function sendManyMessages ($data) {
            $query['data'] = $data;
            return $this->makeRequest('/api/v3/messages/send','POST', $query);
        }

        private function makeRequest ($url, $method, $fields=[]) {

            $fields['email'] = $this->email;
            $fields['password'] = $this->password;

            $url = Apismss::$baseUrl.$url;

            $fieldsString = http_build_query($fields);


            $ch = curl_init();

            if($method == 'POST')
            {
                curl_setopt($ch,CURLOPT_POST, count($fields));
                curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsString);
            }
            else
            {
                $url .= '?'.$fieldsString;
            }

            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_HEADER , false);  // we want headers
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec ($ch);

            $return['response'] = json_decode($result,true);

            if($return['response'] == false)
                $return['response'] = $result;

            $return['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close ($ch);

            return $return;
        }
    }

?>