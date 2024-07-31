<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificados extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
    }
   
    public function checkValidity(){
        $caminho = '/var/www/html/certificadosssl/';
        $arquivos = ['ca_bundle.crt', 'certificate.crt', 'private.key'];
        $dias_limite = 80;
        $data_atual = time();
        $todos_mais_velhos = true;

        foreach ($arquivos as $arquivo) {
            $caminho_arquivo = $caminho . $arquivo;

            if (!file_exists($caminho_arquivo)) {
                //$this->output->set_output("Arquivo $arquivo não existe.");
                echo json_encode(array("message"=>"Arquivo $arquivo não existe."));
                return;
            }

            $data_criacao = filectime($caminho_arquivo);

            if (!$data_criacao) {
                //$this->output->set_output("Não foi possível obter a data de criação do arquivo $arquivo.");
                echo json_encode(array("message"=>"Não foi possível obter a data de criação do arquivo $arquivo."));
                return;
            }

            $dias_arquivo = ($data_atual - $data_criacao) / (60 * 60 * 24);
            if ($dias_arquivo <= $dias_limite) {
                $todos_mais_velhos = false;
                //$this->output->set_output("O arquivo $arquivo tem menos de $dias_limite dias de criação.");
                echo json_encode(array("message"=>"O arquivo $arquivo tem menos de $dias_limite dias de criação."));
                return;
            }
        }

        if ($todos_mais_velhos) {
            //$this->output->set_output("Todos os arquivos têm mais de $dias_limite dias de criação.");
            echo json_encode(array("message"=>"Todos os arquivos têm mais de $dias_limite dias de criação."));
        } else {
            //$this->output->set_output("Nem todos os arquivos têm mais de $dias_limite dias de criação.");
            echo json_encode(array("message"=>"Nem todos os arquivos têm mais de $dias_limite dias de criação."));
        }
    }  
    
}
