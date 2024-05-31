<?php

class Cupom_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->table = "cupom_desconto";
    }

    // Listar dados
    function list()
    {
        //Monta a consultagetByLogin
        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->order_by("id", "DESC");
        //Faz a consulta
        $query = $this->db->get();
        //Retorna todos os registros
        return $query->result();
    }

    // Gravar dados
    function save($object)
    {
        //Inicia a transação
        $this->db->trans_start();
        //Insere o objeto no banco
        $this->db->insert($this->table, $object, true);
        //Pega a mensagem de erro
        $result = $this->db->error();
        //Pega a quantidade de linhas afetada
        $result['lines'] = $this->db->affected_rows();
        //Pega o ultimo id inserido
        //$result['id'] = $this->db->insert_id();
        //Finaliza a transação
        $this->db->trans_complete();
        //Retorna um array com as informações
        return $result;
    }

    public function checkExistingCupom($aluno_id) {
        // Obtendo a data atual do servidor
        $data_atual = date('Y-m-d');
        
        // Verificar se existe um cupom para o aluno na data atual
        $this->db->where('aluno_id', $aluno_id);
        $this->db->where('YEAR(data_cadastro)', date('Y'));
        $this->db->where('MONTH(data_cadastro)', date('m'));
        $this->db->where('DAY(data_cadastro)', date('d'));
        $this->db->where('status', 1);
        $query = $this->db->get('cupom_desconto');
        
        return $query->num_rows() > 0;
    }

    public function expiredStatusUpdate() {
        // Obtém a data atual do servidor
        $data_atual = date('Y-m-d');

        // Monta a query para atualizar os registros
        $this->db->set('status', 0);
        $this->db->where('data_validade <', $data_atual);
        $this->db->update('cupom_desconto');

        // Retorna o número de linhas afetadas pela atualização
        return $this->db->affected_rows();
    }

}
