<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

class EmailSend extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function sendEmail() {
        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => 'mlsn.9023e774d8fbc73e63223fdd4f9369d80f7b8463f0f5ef124aea005d81e7af6c']);

        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient('angelolefundes@yahoo.com.br', 'Angelo Lefundes'),
        ];

        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br')
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject('Redefiição de senha ECOCURSOS')
            ->setHtml('Teste html <a href="#">Clique aqui</a>')
            ->setText('This is the text content');
            //->setReplyTo('reply to')
            //->setReplyToName('reply to name');

        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
}
