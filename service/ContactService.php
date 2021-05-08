<?php

class ContactService {
    
    private $em;
    private $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

    public function __construct() 
    {
        $this->em  = new mysqli('localhost', 'root', '', 'website');
    }

    public function conection() 
    {
        $response = ['status' => true];

        if (!$this->em) {
            $response = ['status' => false, 'message' => 'Ocorreu um erro na conexão ' . mysqli_connect_error()];
        }

        $this->response = $response;

        return $response;
    }

    public function insert($request)
    {
        $token = isset($request['token']) ? $request['token'] : null;
        $name = isset($request['name']) ? $request['name'] : null;
        $city = isset($request['city']) ? $request['city'] : null;
        $state = isset($request['state']) ? $request['state'] : null;
        $mail = isset($request['mail']) ? $request['mail'] : null;
        $consumption = isset($request['consumption']) ? $request['consumption'] : null;
        $company = isset($request['company']) ? $request['company'] : null;
        $is_send_mail = true;
        
        $error = [];
        if ($token) {
            if ($token !== $this->token) {       
                $error['token'] = 'Token inválido.';
            }
        } else {
            $error['token'] = 'O Token é obrigatório.';
        }

        if (!$name) {
            $error['name'] = 'O nome é obrigatório.';
        } 

        if (!$city) {
            $error['city'] = 'A cidade é obrigatório.';
        } 

        if (!$state) {
            $error['state'] = 'O estado é obrigatório.';
        } 

        if (!$mail) {
            $error['mail'] = 'O e-mail é obrigatório.';
        } 

        if (!$consumption) {
            $error['consumption'] = 'O consumo é obrigatório.';
        } 

        if (!$company) {
            $error['company'] = 'A empresa é obrigatório.';
        } 

        if (count($error) > 0) {
            $response = ['status' => false, 'message' => 'Campos obrigatórios.'];
            return $error;
        }
                
        $sql = "INSERT INTO contact (name, city, state, mail, consumption, company, is_send_mail, created_at)
                    VALUES ('$name', '$city', '$state', '$mail', '$consumption' , '$company', $is_send_mail, '2021-05-08 03:27:05');";
        
        if ($this->em->query($sql) === true) {
            $response = ['status' => true];
        } else {
            $response = ['status' => false, 'message' => 'Ocorreu um erro na conexão ' . mysqli_connect_error()];
        }

        return $response;
    }  

    public function sendMail() 
    {
        
    }
}