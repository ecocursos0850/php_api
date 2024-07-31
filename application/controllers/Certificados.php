<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificados extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
    }
   
    public function checkValidity(){
        // Atualize o caminho dos arquivos para os novos caminhos fornecidos
        $caminho = '/etc/ssl/';
        $arquivos = [
            'certificate.crt',
            'ca_bundle.crt',
            'private/private.key' // Nota: O caminho do arquivo 'private.key' inclui um diretório adicional
        ];
        $dias_limite = 80;
        $data_atual = time();
        $todos_mais_velhos = true;
    
        foreach ($arquivos as $arquivo) {
            $caminho_arquivo = $caminho . $arquivo;
    
            if (!file_exists($caminho_arquivo)) {
                echo json_encode([
                    "error" => "error",
                    "message" => "Arquivo $arquivo não encontrado."
                ]);
                return;
            }
    
            // 'filectime' retorna o tempo de criação, mas pode não estar disponível em todos os sistemas de arquivos.
            // Você pode considerar usar 'filemtime' para obter o tempo da última modificação como uma alternativa.
            $data_criacao = filectime($caminho_arquivo);
    
            if ($data_criacao === false) {
                echo json_encode([
                    "error" => "error",
                    "message" => "Não foi possível obter a data de criação do arquivo $arquivo."
                ]);
                return;
            }
    
            $dias_arquivo = ($data_atual - $data_criacao) / (60 * 60 * 24);
            if ($dias_arquivo <= $dias_limite) {
                $todos_mais_velhos = false;
                echo json_encode([
                    "error" => "success",
                    "message" => "Certificado SSL da API válido."
                ]);
                return;
            }
        }
    
        if ($todos_mais_velhos) {
            echo json_encode([
                "error" => "error",
                "message" => "O certificado SSL da API precisa ser renovado."
            ]);
        } else {
            echo json_encode([
                "error" => "success",
                "message" => "O certificado não tem mais de $dias_limite dias de emissão."
            ]);
        }
    }
    
}
