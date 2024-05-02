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
        $destinatario = $this->input->post("destinatario"); 
        $assunto = $this->input->post("assunto"); 
        $mensagem = $this->input->post("mensagem");
        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => 'mlsn.9023e774d8fbc73e63223fdd4f9369d80f7b8463f0f5ef124aea005d81e7af6c']);
        //$mailerSend = new MailerSend(['api_key' => 'mlsn.a1e2dfc2bf70ec097b48d7e0d88e11f92fc08691d6092896e161fc2ce631c4e6']);
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($destinatario, $destinatario),
        ];

        $emailParams = (new EmailParams())
            //->setFrom('MS_k7Zj7A@trial-pxkjn41xew0gz781.mlsender.net')
            ->setFrom('diretoria@ecocursos.com.br')
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($assunto)
            ->setHtml($mensagem)
            ->setText($mensagem);
            //->setReplyTo('reply to')
            //->setReplyToName('reply to name');

        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
}
