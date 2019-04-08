<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receitas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function exibir() {
        $this->db->select('*');
        return $this->db->get('receitas')->result_array();
    }

    public function ingredientes($ids = array()) {
        $this->db->select('nome');
        $this->db->where_in('id', $ids);
        $query = $this->db->get('produtos')->result_array();
        return $query;
    }

    public function checkReceita($ingredientes = array()) {
        $this->db->select('*');
        $this->db->like($ingredientes);
        $query = $this->db->get('receitas')->result_array();
        return $query;
    }

    public function set_receita($data = array()) {
        $this->db->insert('receitas', $data);
        return $this->db->insert_id();
    }

    public function updtImagem($id) {
        $this->db->set('imagem', $id);
        $this->db->where('id', $id);
        if ($this->db->update('receitas')){
            return true;
        } else {
            return false;
        }
    }
    
    public function excluir($data = array()) {
        $this->db->where_in('id', $data);
        if ($this->db->delete('receitas')){
            return true;
        } else {
            return false;
        }
        
    }
    
    public function get_nomereceita($ids = array()) {
        $this->db->select('nome');
        $this->db->where_in('id', $ids);
        $query = $this->db->get('receitas');
        return $query->result();
    }

}
