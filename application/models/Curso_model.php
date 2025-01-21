<?php

class Curso_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = "curso";
    }

    public function get_curso_by_id($curso_id)
    {
        $this->db->where('id', $curso_id);
        $query = $this->db->get($this->table);
        return $query->row(); // Retorna um único curso
    }

}
