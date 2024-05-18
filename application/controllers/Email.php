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
        $mailerSend = new MailerSend(['api_key' => 'mlsn.37bf59de3d901608d0193503effb3d311adad7ac6d6dc8e6fd4e8e552dca9420']); //oficial
        //$mailerSend = new MailerSend(['api_key' => 'mlsn.73b8de9d6a0704863ff441c8a0f491a2f8dc4e24f0e24b181a916ac2ec78e32b']); //mailersend
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($destinatario, $destinatario),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($assunto)
            ->setHtml($mensagem)
            ->setText($mensagem);
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
    // Teste de envio de email
    public function sendTeste() {
        
        // Extraia os dados do array associativo
        $destinatario = "angelolefundes@yahoo.com.br";
        $assunto = "Redefinição de senha[TESTE]";
        
        $mensagem_html = $this->load->view('email/resetPassword', $data_view, true);
    
        // Carregar a view e capturar o conteúdo como uma string
        $data_view = array(
            'destinatario' => $destinatario,
            'assunto' => $assunto,
            'mensagem' => $mensagem_html
        );
    
        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => '']); //oficial
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($destinatario, $destinatario),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($assunto)
            ->setHtml($mensagem_html)
            ->setText(strip_tags($mensagem_html)); // Usar texto simples sem HTML
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
        
}
