<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Alunos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Alunos_model");
        $this->load->model("Matricula_model");
        $this->load->model("Curso_model");
        $this->load->model("Declaracao_model");
        // Defina os cabeçalhos CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
    }

    public function index() {
        $this->load->view('aluno_upload');
    }

    public function uploadExcel() {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar o arquivo.']);
            return;
        }

        $filePath = $_FILES['file']['tmp_name'];

        //require_once APPPATH . 'third_party/PhpSpreadsheet/vendor/autoload.php';
        $reader = new Xlsx();
        $spreadsheet = $reader->load($filePath);

        if (count($data) < 2) {
            echo json_encode(['status' => 'error', 'message' => 'Arquivo Excel inválido.']);
            return;
        }

        $result = [];
        foreach (array_slice($data, 1) as $row) {
            $result[] = [
                'cpf' => trim($row[0]),
                'email' => trim($row[1]),
                'parceiro_id' => trim($row[2]),
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function atualizarParceiro() {
        $dados = json_decode($this->input->raw_input_stream, true);
        
        if (empty($dados)) {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum dado enviado.']);
            return;
        }

        $atualizado = $this->Aluno_model->atualizarParceiro($dados);

        if ($atualizado) {
            echo json_encode(['status' => 'success', 'message' => 'Dados atualizados com sucesso!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar os dados.']);
        }
    }

    public function get_matriculas_por_cpf($cpf)
    {
        // Obtenha as informações do aluno
        $aluno = $this->Alunos_model->get_aluno_by_cpf($cpf);

        if (empty($aluno)) {
            echo json_encode(['error' => 'Aluno não encontrado']);
            return;
        }

        // Obtenha as matrículas do aluno
        $matriculas = $this->Matricula_model->get_matriculas_by_aluno_id($aluno[0]->id);

        if (empty($matriculas)) {
            echo json_encode(['error' => 'Nenhuma matrícula encontrada']);
            return;
        }

        // Obtenha os cursos relacionados às matrículas
        $cursos = [];
        foreach ($matriculas as $matricula) {
            $curso = $this->Curso_model->get_curso_by_id($matricula->curso_id);
        
            if ($curso) {
                // Verifica se existe uma declaração para este aluno, curso e matrícula
                $declaracao_existe = $this->Declaracao_model->existe_declaracao($aluno[0]->id, $curso->id, $matricula->id);
        
                if (!$declaracao_existe) {
                    $cursos[] = [
                        'aluno_id' => $aluno[0]->id,
                        'matricula_id' => $matricula->id,
                        'curso_id' => $curso->id,
                        'titulo' => $curso->titulo
                    ];
                }
            }
        }

        echo json_encode($cursos);
    }
   
}
