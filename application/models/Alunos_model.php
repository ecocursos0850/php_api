<?php

class Alunos_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = "aluno";
    }

    // Listar dados para aniversariantes do mês
    function listHappyBirthday()
    {
        // Monta a consulta
        $this->db->select("id, nome, email");
        $this->db->from($this->table);
        $this->db->where('MONTH(data_nascimento)', date('m'));
        $this->db->where('DAY(data_nascimento)', date('d'));
        $this->db->where('email_aniversario', 1);
        $this->db->order_by("data_nascimento", "ASC");
        // Faz a consulta
        $query = $this->db->get();
        // Retorna todos os registros
        return $query->result();
    }

    // Gravar dados
    function save($object)
    {
        // Inicia a transação
        $this->db->trans_start();
        // Insere o objeto no banco
        $this->db->insert($this->table, $object, true);
        // Pega a mensagem de erro
        $result = $this->db->error();
        // Pega a quantidade de linhas afetada
        $result['lines'] = $this->db->affected_rows();
        // Pega o ultimo id inserido
        //$result['id'] = $this->db->insert_id();
        // Finaliza a transação
        $this->db->trans_complete();
        // Retorna um array com as informações
        return $result;
    }
        
}