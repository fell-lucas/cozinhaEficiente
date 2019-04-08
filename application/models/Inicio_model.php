<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_produtos() {
        $json = [];
        $this->db->like('nome', $this->input->get("q"));
        $query = $this->db->select('id,nome as text')
                ->get("produtos");
        $json = $query->result();
        return json_encode($json);
    }

    public function get_produtos_usuario_idprod($id_produto) {
        $this->db->select('id_produto');
        $this->db->where('id_usuario', $_SESSION['id']);
        $this->db->where('id_produto', $id_produto);
        $query = $this->db->get('produtos_usuario');
        return $query->num_rows();
    }

    public function get_produtos_usuario() {
        $this->db->select('*');
        $this->db->where('id_usuario', $_SESSION['id']);
        $this->db->order_by('nomeprod asc');
        $query = $this->db->get('produtos_usuario_view');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_produtos_porid($idproduto = array()) {
        $this->db->select('nomeprod');
        $this->db->where_in('id', $idproduto);
        $query = $this->db->get('produtos_usuario_view');
        return $query->result();
    }

    public function set_comida($data = array()) {
        $this->db->insert('produtos_usuario', $data);
        return $this->db->insert_id();
    }

    public function set_comida_adm($data = array()) {
        $this->db->insert('produtos', $data);
        return $this->db->insert_id();
    }

    public function excluir($data = array()) {
        $this->db->where_in('id', $data);
        if ($this->db->delete('produtos_usuario')) {
            return true;
        } else {
            return false;
        }
    }

    public function consumir($id) {
        if ($this->quantidade($id) > 0) {
            $this->db->where('id', $id);
            $this->db->set('quantidade', 'quantidade-1', FALSE);
            return $this->db->update('produtos_usuario');
        }
    }
    public function quantidade($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('produtos_usuario')->result_array();
        return $query[0]['quantidade'];
    }
    public function vencido($id, $data) {
        $this->db->set('venceu', $data);
        $this->db->where('id', $id);
        if ($this->db->update('produtos_usuario')) {
            return true;
        } else {
            return false;
        }
    }

}
