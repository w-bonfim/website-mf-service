<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class ContactService {
    
    private $em;
    private $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

    public function __construct() 
    {
        $this->em  = new mysqli('localhost', 'root', '', 'mr_solis');
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
        $is_send_mail = false;
        
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

        //Disparo de e-mail
        $send_mail = $this->sendMail($request); 

        if ($send_mail['status']) {
            $is_send_mail = true;
        }
       
        $date = date('Y-m-d h:i:s');
        $sql = "INSERT INTO contact (name, city, state, mail, consumption, company, is_send_mail, created_at)
                    VALUES ('$name', '$city', '$state', '$mail', '$consumption' , '$company', $is_send_mail, '{$date}');";
        
        if ($this->em->query($sql) === true) {
            $response = ['status' => true];
        } else {
            $response = ['status' => false, 'message' => 'Ocorreu um erro na conexão ' . mysqli_connect_error()];
        }

        return $response;
    }  

    public function sendMail($request) 
    {
        //Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //configuração e-mail
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                   
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mrsolis.eletrica@gmail.com';
            $mail->Password   = 'm&rsolis2021';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('mrsolis.eletrica@gmail.com', 'MR Solis');       
            $mail->addAddress('mrsolis.eletrica@gmail.com', 'MR Solis');  //Adicionando um destinatário, (o nome é opcional)
            $mail->addReplyTo($request['mail'], $request['name']);
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Anexo     
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); //O nome é opcional

            $body = '<h1>Informações de Contato</h1>';
            $body .= '<strong>Nome: </strong> ' . $request['name'] . '<br>';
            $body .= '<strong>Cidade: </strong>' . $request['city'] . '<br>';
            $body .= '<strong>Estado: </strong>' . $request['state'] . '<br>';
            $body .= '<strong>E-mail: </strong>' . $request['mail'] . '<br>';
            $body .= '<strong>Consumo Mensal: </strong>' . $request['consumption'] . '<br>';
            $body .= '<strong>Concessionária de Energia: </strong> ' . $request['company'] . '<br>';
            
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = 'Simulação Eletrica - Site';
            $mail->Body    = $body;
            //$mail->AltBody = '';

            $mail->send();
            $response = ['status' => true, 'message' => 'success'];
        } catch (Exception $e) {
            $response = ['status' => false, 'message' => 'Ops! E-mail não enviado, tente novamente mais tarde.'];
        }

        return $response;
    }
}