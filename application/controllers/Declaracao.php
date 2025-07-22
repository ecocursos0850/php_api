<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Declaracao extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->model("Declaracao_model");
        $this->load->model("Matricula_model");
        // Defina os cabeçalhos CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function index($hash = null) {
        try {
            if (empty($hash)) {
                throw new Exception("Parâmetro ausente.");
            }

            // Decodifica o hash da URL
            $base64 = urldecode($hash);

            // Decodifica de base64 para JSON string
            $jsonString = base64_decode($base64, true);
            if ($jsonString === false) {
                throw new Exception("Falha ao decodificar base64.");
            }

            // Converte JSON string em objeto PHP
            $data = json_decode($jsonString);
            if (!isset($data->id) || !isset($data->timestamp)) {
                throw new Exception("Dados inválidos ou incompletos.");
            }

            // Verifica validade do token (5 minutos)
            $agora = round(microtime(true) * 1000); // milissegundos
            if ($agora - $data->timestamp > 5 * 60 * 1000) {
                throw new Exception("Token expirado.");
            }

            $matricula_id = (int)$data->id;
            if ($matricula_id <= 0) {
                throw new Exception("ID de matrícula inválido.");
            }

            // Consulta a matrícula
            $this->db->select('matricula.*, aluno.*, curso.titulo as curso_titulo, curso.carga_horaria');
            $this->db->from('matricula');
            $this->db->join('aluno', 'aluno.id = matricula.aluno_id');
            $this->db->join('curso', 'curso.id = matricula.curso_id');
            $this->db->where('matricula.id', $matricula_id);
            $query = $this->db->get();

            if ($query->num_rows() === 0) {
                throw new Exception("Matrícula não encontrada.");
            }

            $dados['matricula_info'] = $query->row();
            $this->load->view("declaracao", $dados);

        } catch (Exception $e) {
            log_message('error', 'Erro na declaração: ' . $e->getMessage());
            show_error($e->getMessage(), 403, 'Acesso não autorizado');
        }
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
    
    public function gravar() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        print_r($_POST, true);
        die();
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método não permitido", 405);
            }
    
            log_message('error', 'POST: ' . print_r($_POST, true));
            log_message('error', 'FILES: ' . print_r($_FILES, true));

            $inicioPeriodo = $this->input->post('inicioPeriodo', true);
            $finalPeriodo = $this->input->post('finalPeriodo', true);
            $aluno_id = (int)$this->input->post('aluno_id', true);
            $curso_id = (int)$this->input->post('curso_id', true);
            $matricula_id = (int)$this->input->post('matricula_id', true);
    
            $errors = [];
            if (empty($inicioPeriodo)) $errors[] = 'Data de início é obrigatória';
            if (empty($finalPeriodo)) $errors[] = 'Data final é obrigatória';
            if ($aluno_id <= 0) $errors[] = 'ID do aluno inválido';
            if ($curso_id <= 0) $errors[] = 'ID do curso inválido';
            if ($matricula_id <= 0) $errors[] = 'ID da matrícula inválido';
    
            if (!empty($errors)) {
                throw new Exception(implode("\n", $errors), 400);
            }
    
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Erro no envio do arquivo. Código: " . ($_FILES['file']['error'] ?? 'N/A'), 400);
            }
    
            $file = $_FILES['file'];
            $allowedMimeTypes = [
                'application/pdf' => 'pdf',
                'image/jpeg' => 'jpg',
                'image/jpg' => 'jpg',
                'image/png' => 'png'
            ];
    
            $maxSize = 5 * 1024 * 1024;
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $file['tmp_name']);
            finfo_close($fileInfo);
    
            if (!array_key_exists($mimeType, $allowedMimeTypes)) {
                throw new Exception("Tipo de arquivo inválido. Apenas PDF, JPG e PNG são permitidos.", 400);
            }
    
            if ($file['size'] > $maxSize) {
                throw new Exception("Tamanho do arquivo excede o limite de 5MB.", 400);
            }
    
            $uploadPath = '/var/www/html/Declaracao/';
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0755, true)) {
                    throw new Exception("Falha ao criar diretório de upload.", 500);
                }
            }
    
            $fileExtension = $allowedMimeTypes[$mimeType];
            $fileName = 'decl_' . $matricula_id . '_' . time() . '.' . $fileExtension;
            $filePath = $uploadPath . $fileName;
    
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new Exception("Falha ao salvar o arquivo.", 500);
            }
    
            chmod($filePath, 0644);
    
            $data = [
                'anexo_comprovante' => $fileName,
                'aprovado' => (int)false,
                'data_cadastro' => date('Y-m-d'),
                'inicio_periodo' => date('Y-m-d', strtotime($inicioPeriodo)),
                'final_periodo' => date('Y-m-d', strtotime($finalPeriodo)),
                'aluno_id' => $aluno_id,
                'curso_id' => $curso_id,
                'matricula_id' => $matricula_id,
                'status' => 1,
                'valor' => 50.00
            ];
    
            $existing = $this->db->get_where('declaracao_matricula', [
                'aluno_id' => $aluno_id,
                'curso_id' => $curso_id,
                'matricula_id' => $matricula_id
            ])->row();
    
            if ($existing) {
                if (!empty($existing->anexo_comprovante)) {
                    $oldFilePath = $uploadPath . $existing->anexo_comprovante;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
    
                $this->db->where('id', $existing->id);
                $result = $this->db->update('declaracao_matricula', $data);
                $declaracao_id = $existing->id;
            } else {
                $result = $this->db->insert('declaracao_matricula', $data);
                $declaracao_id = $this->db->insert_id();
            }
    
            if (!$result) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                throw new Exception("Falha ao registrar no banco de dados: " . $this->db->error()['message'], 500);
            }
    
            $response = [
                'success' => true,
                'message' => 'Declaração registrada com sucesso!',
                'protocolo' => $declaracao_id,
                'arquivo' => $fileName
            ];
    
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            } else {
                $this->load->view('declaracao_sucesso', $response);
            }
    
        } catch (Exception $e) {
            $errorCode = $e->getCode() ?: 500;
            log_message('error', 'Erro em declaracao/gravar: ' . $e->getMessage());
    
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_status_header($errorCode)
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'error' => $e->getMessage()
                    ]));
            } else {
                $this->load->view('declaracao_erro', [
                    'mensagem' => $e->getMessage(),
                    'codigo' => $errorCode
                ]);
            }
        }
    }
    
    
}
