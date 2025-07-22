<?php

class Matricula_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = "matricula";
    }

    public function get_matriculas_by_aluno_id($aluno_id)
    {
        $this->db->where('aluno_id', $aluno_id);
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function getById($matricula)
    {
        $this->db->where('id', $matricula);
        $query = $this->db->get($this->table);
        return $query->result();
    }

}
