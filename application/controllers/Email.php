<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/../../vendor/autoload.php';

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

//require_once APPPATH . 'vendor/autoload.php'; // Caminho para o autoload.php

class Email extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function send() {
        // Obtenha o corpo da requisição POST enviado como JSON
        $json_data = file_get_contents('php://input');
    
        // Decodifique os dados JSON para um array associativo
        $data = json_decode($json_data, true);
    
        // Verifique se os dados foram decodificados corretamente
        if ($data === null) {
            // Se não foi possível decodificar os dados JSON, retorne um erro
            http_response_code(400); // Bad Request
            echo "Erro: os dados JSON estão malformados.";
            return;
        }
    
        // Extraia os dados do array associativo
        $destinatario = $data["destinatario"]; 
        $assunto = $data["assunto"]; 
        $mensagem = $data["mensagem"];
    
        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => 'mlsn.9023e774d8fbc73e63223fdd4f9369d80f7b8463f0f5ef124aea005d81e7af6c']);
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($destinatario, $destinatario),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br')
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($assunto)
            ->setHtml($mensagem)
            ->setText($mensagem);
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
    
}
