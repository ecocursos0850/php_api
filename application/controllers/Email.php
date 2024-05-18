<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/../../vendor/autoload.php';

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Email extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->library('twig');
    }

    public function resetPassword() {
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
    
        // Dados para a view
        $dados = [
            'destinatario' => $data["destinatario"],
            'assunto' => $data["assunto"],
            'nome' => $data["nome"], // Adicione a variável senha
            'senha' => $data["senha"], // Adicione a variável senha
            'copy' => date("Y") // Adicione a variável senha
        ];

        // Renderize a view e capture o conteúdo como string
        try {
            $mensagem_html = $this->twig->render('email/resetPassword.twig', $dados);
        } catch (Exception $e) {
            log_message('error', 'Erro ao renderizar o template: ' . $e->getMessage());
            show_error('Erro ao renderizar o template: ' . $e->getMessage(), 500);
            return;
        }

        // Inicialize a biblioteca MailerSend
        $api_key = getenv('MAILERSEND_API_KEY'); // Use a variável de ambiente para a API key
        if (!$api_key) {
            http_response_code(500); // Internal Server Error
            echo "Erro: a chave da API não está configurada.";
            return;
        }


        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => $api_key]); //oficial
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($dados['destinatario'], $dados['destinatario']),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($dados["assunto"])
            ->setHtml($mensagem_html);
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }

    public function happyBirthday() {
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
    
        // Dados para a view
        $dados = [
            'destinatario' => $data["destinatario"],
            'assunto' => $data["assunto"],
            'nome' => $data["nome"], // Adicione a variável senha
            'presente' => $data["presente"], // Adicione a variável senha
            'copy' => date("Y") // Adicione a variável senha
        ];

        // Renderize a view e capture o conteúdo como string
        try {
            $mensagem_html = $this->twig->render('email/happyBirthday.twig', $dados);
        } catch (Exception $e) {
            log_message('error', 'Erro ao renderizar o template: ' . $e->getMessage());
            show_error('Erro ao renderizar o template: ' . $e->getMessage(), 500);
            return;
        }

        // Inicialize a biblioteca MailerSend
        $api_key = getenv('MAILERSEND_API_KEY'); // Use a variável de ambiente para a API key
        if (!$api_key) {
            http_response_code(500); // Internal Server Error
            echo "Erro: a chave da API não está configurada.";
            return;
        }


        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => $api_key]); //oficial
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($dados['destinatario'], $dados['destinatario']),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($dados["assunto"])
            ->setHtml($mensagem_html);
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }

    public function requestPostgraduate(){
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
    
        // Dados para a view
        $dados = [
            'destinatario' => $data["destinatario"],
            'assunto' => $data["assunto"],
            'login' => $data["login"], // Adicione a variável login
            'senha' => $data["senha"], // Adicione a variável senha
            'copy' => date("Y") // Adicione a variável copy
        ];

        // Renderize a view e capture o conteúdo como string
        try {
            $mensagem_html = $this->twig->render('email/requestPostgraduate.twig', $dados);
        } catch (Exception $e) {
            log_message('error', 'Erro ao renderizar o template: ' . $e->getMessage());
            show_error('Erro ao renderizar o template: ' . $e->getMessage(), 500);
            return;
        }

        // Inicialize a biblioteca MailerSend
        $api_key = getenv('MAILERSEND_API_KEY'); // Use a variável de ambiente para a API key
        if (!$api_key) {
            http_response_code(500); // Internal Server Error
            echo "Erro: a chave da API não está configurada.";
            return;
        }


        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => $api_key]); //oficial
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($dados['destinatario'], $dados['destinatario']),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($dados["assunto"])
            ->setHtml($mensagem_html);
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);        
    }

    // Teste de envio de email
    public function resetPasswordTest() {
        
        // Dados para a view
        $dados = [
            'destinatario' => "angelolefundes@yahoo.com.br",
            'assunto' => "Redefinição de senha[TESTE]",
            'nome' => "Angelo Lefundes", // Adicione a variável senha
            'senha' => "qwerty", // Adicione a variável senha
            'copy' => date("Y") // Adicione a variável senha
        ];

        // Renderize a view e capture o conteúdo como string
        try {
            $mensagem_html = $this->twig->render('email/resetPassword.twig', $dados);
        } catch (Exception $e) {
            log_message('error', 'Erro ao renderizar o template: ' . $e->getMessage());
            show_error('Erro ao renderizar o template: ' . $e->getMessage(), 500);
            return;
        }

        // Inicialize a biblioteca MailerSend
        $api_key = getenv('MAILERSEND_API_KEY'); // Use a variável de ambiente para a API key
        if (!$api_key) {
            http_response_code(500); // Internal Server Error
            echo "Erro: a chave da API não está configurada.";
            return;
        }


        // Inicialize a biblioteca MailerSend
        $mailerSend = new MailerSend(['api_key' => $api_key]); //oficial
    
        // Configurar destinatários e parâmetros de e-mail
        $recipients = [
            new Recipient($dados['destinatario'], $dados['destinatario']),
        ];
    
        $emailParams = (new EmailParams())
            ->setFrom('diretoria@ecocursos.com.br') //oficial
            ->setFromName('ECOCURSOS')
            ->setRecipients($recipients)
            ->setSubject($dados["assunto"])
            ->setHtml($mensagem_html)
            ->setText(strip_tags($mensagem_html)); // Usar texto simples sem HTML
    
        // Enviar e-mail usando a biblioteca MailerSend
        $mailerSend->email->send($emailParams);
    }
        
}
