<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Declaracao extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model("Declaracao_model");
        // Defina os cabeçalhos CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function salvar()
    {
        // Verifica se é um método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            show_error('Método não permitido', 405);
        }
    
        // Recebe os dados do formulário e valida campos obrigatórios
        $inicioPeriodo = $this->input->post('inicioPeriodo', true);
        $finalPeriodo = $this->input->post('finalPeriodo', true);
        $aluno_id = $this->input->post('aluno_id', true);
        $curso_id = $this->input->post('curso_id', true);
        $matricula_id = $this->input->post('matricula_id', true);
    
        // Verifica se os campos obrigatórios estão presentes
        if (empty($inicioPeriodo) || empty($finalPeriodo) || empty($aluno_id) || empty($curso_id) || empty($matricula_id)) {
            show_error('Todos os campos são obrigatórios', 400);
        }
    
        // Verifica se um arquivo foi enviado
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            show_error('Erro no envio do arquivo', 400);
        }
    
        $file = $_FILES['file'];
    
        // Verifica tipo e tamanho do arquivo (exemplo: máximo de 5MB e tipos permitidos)
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $allowedTypes) || $file['size'] > 5 * 1024 * 1024) {
            show_error('Arquivo inválido. Apenas PDF, JPG, JPEG e PNG são permitidos, com tamanho máximo de 5MB.', 400);
        }
    
        // Define o caminho de upload
        $uploadPath = FCPATH . 'uploads/declaracoes/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
    
        // Gera um nome único para o arquivo
        $fileHash = hash('sha256', $file['name'] . time());
        $fileName = $fileHash . '.' . $fileExtension;
        $filePath = $uploadPath . $fileName;
    
        // Move o arquivo para o destino
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            show_error('Erro ao salvar o arquivo', 500);
        }
    
        // Define as permissões de leitura
        chmod($filePath, 0644);
    
        // Insere os dados no banco de dados
        $data = [
            'anexo_comprovante' => $fileName,
            'aprovado' => 0, // Padrão: não aprovado
            'data_cadastro' => date('Y-m-d'),
            'inicio_periodo' => $inicioPeriodo,
            'final_periodo' => $finalPeriodo,
            'aluno_id' => $aluno_id,
            'curso_id' => $curso_id,
            'matricula_id' => $matricula_id,
            'status' => 1, // Status ativo por padrão
        ];
    
        // Usar transações para evitar inconsistências
        $this->db->trans_start();
        $result = $this->Declaracao_matricula->inserir($data);
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === false || $result['lines'] <= 0) {
            show_error('Erro ao solicitar declaração: ' . ($result['message'] ?? 'Erro desconhecido'), 400);
        }
    
        // Retorna uma resposta de sucesso
        return $this->output
            ->set_status_header(201)
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'message' => 'Declaração solicitada com sucesso!',
                'id' => $result["id"]
            ]));
    }
    
}
