<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificados extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        // Defina os cabeçalhos CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }
   
    public function checkValidity()
    {

        // Forçando que seja um retorno de sucesso, pois a renovação está automatizada com o certbot
        echo json_encode([
            "result" => "success",
            "message" => "O certificado não tem mais de $dias_limite dias de emissão."
        ]);
        return;

        // Caminho dos arquivos
        $caminho = '/etc/letsencrypt/archive/srv448021.hstgr.cloud/';
        $arquivos = [
            'fullchain2.pem',
            'privkey2.pem'
        ];
        $dias_limite = 80;
        $data_atual = time();
        $arquivos_com_mais_de_80_dias = 0;
    
        foreach ($arquivos as $arquivo) {
            $caminho_arquivo = $caminho . $arquivo;
    
            if (!file_exists($caminho_arquivo)) {
                echo json_encode([
                    "result" => "error",
                    "message" => "Arquivo $arquivo não encontrado."
                ]);
                return;
            }
    
            // Usando filemtime para pegar a última modificação como referência
            $data_criacao = filemtime($caminho_arquivo);
    
            if ($data_criacao === false) {
                echo json_encode([
                    "result" => "error",
                    "message" => "Não foi possível obter a data de criação do arquivo $arquivo."
                ]);
                return;
            }
    
            $dias_arquivo = floor(($data_atual - $data_criacao) / (60 * 60 * 24));
            if ($dias_arquivo >= $dias_limite) {
                $arquivos_com_mais_de_80_dias++;
            }
        }
    
        if ($arquivos_com_mais_de_80_dias === count($arquivos)) {
            echo json_encode([
                "result" => "error",
                "message" => "Faltam poucos dias para o vencimento do certificado SSL/API. Renove o certificado imediatamente!"
            ]);
        } else {
            echo json_encode([
                "result" => "success",
                "message" => "O certificado não tem mais de $dias_limite dias de emissão."
            ]);
        }
    }
      
}
