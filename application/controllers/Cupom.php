<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'vendor/autoload.php'; // Caminho para o autoload.php

class Cupom extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Cupom_model");
    }

    public function expiredStatusUpdate() {
        // Chama o método do model para atualizar os cupons
        $linhas_afetadas = $this->Cupom_model->expiredStatusUpdate();

        // Verifica se a atualização foi bem-sucedida
        if ($linhas_afetadas > 0) {
            return "Status dos cupons atualizado com sucesso!";
        } else {
            return "Nenhum cupom foi atualizado.";
        }
    }    
    
}
