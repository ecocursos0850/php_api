<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alunos extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Alunos_model");
        // Defina os cabeçalhos CORS
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
                $cursos[] = [
                    'aluno_id' => $aluno[0]->id,
                    'matricula_id' => $matricula->id,
                    'curso_id' => $curso->id,
                    'titulo' => $curso->titulo
                ];
            }
        }

        echo json_encode($cursos);
    }
   
}
