<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Declaracao_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insere um novo registro na tabela declaracao_matricula.
     *
     * @param array $data Dados a serem inseridos.
     * @return int|bool Retorna o ID do registro inserido ou false em caso de falha.
     */
    public function inserir($data)
    {
        // Inicia a transação
        $this->db->trans_start();
    
        // Insere os dados no banco
        $this->db->insert('declaracao_matricula', $data);
    
        // Captura a mensagem de erro (se houver)
        $result = $this->db->error();
    
        // Captura o número de linhas afetadas
        $result['lines'] = $this->db->affected_rows();
    
        // Captura o último ID inserido
        $result['id'] = $this->db->insert_id();
    
        // Finaliza a transação
        $this->db->trans_complete();
    
        // Retorna as informações do resultado
        return $result;
    }

    /**
     * Atualiza um registro na tabela declaracao_matricula.
     *
     * @param int $id ID do registro a ser atualizado.
     * @param array $data Dados a serem atualizados.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function atualizar($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('declaracao_matricula', $data);
    }

    /**
     * Obtém um registro pelo ID.
     *
     * @param int $id ID do registro a ser obtido.
     * @return object|bool Retorna o registro como objeto ou false se não encontrado.
     */
    public function obterPorId($id)
    {
        $query = $this->db->get_where('declaracao_matricula', ['id' => $id]);
        return $query->row(); // Retorna a primeira linha como objeto
    }

    /**
     * Remove um registro da tabela declaracao_matricula.
     *
     * @param int $id ID do registro a ser removido.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function remover($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('declaracao_matricula');
    }

    /**
     * Obtém todos os registros da tabela declaracao_matricula.
     *
     * @param array|null $filtros Filtros opcionais para a busca.
     * @return array Retorna uma lista de registros como array de objetos.
     */
    public function obterTodos($filtros = null)
    {
        if ($filtros) {
            $this->db->where($filtros);
        }
        $query = $this->db->get('declaracao_matricula');
        return $query->result(); // Retorna todos os resultados como um array de objetos
    }

    public function existe_declaracao($aluno_id, $curso_id, $matricula_id) {
        $this->db->where('aluno_id', $aluno_id);
        $this->db->where('curso_id', $curso_id);
        $this->db->where('matricula_id', $matricula_id);
        $query = $this->db->get('declaracao_matricula');

        return $query->num_rows() > 0; // Retorna true se houver registros
    }    
}
