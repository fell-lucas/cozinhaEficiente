<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categoria_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_categoria() {
        $json = [];
            $this->db->like('nome', $this->input->get("q"));
            $query = $this->db->select('id,nome as text')
                    ->get("categorias");
            $json = $query->result();
            return json_encode($json);
    }
    
    public function set_categoria($data = array()) {
        $this->db->insert('categorias', $data);
        return $this->db->insert_id();
    }
    public function update_pessoa($idpessoa, $data) {
        $this->db->where('idpessoa', $idpessoa);
        $this->db->update('pessoa_f', $data);
        return true;
    }

    public function detalhes($idpessoa) {
        $this->db->select('endereco');
        $this->db->where('idpessoa', $idpessoa);
        $query = $this->db->get('pessoaf_endereco');
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    

    public function set_endereco_fkey($data = array()) {
        $this->db->insert('endereco', $data);
    }

    public function update_endereco_fkey($where, $data = array()) {
        $this->db->where('pessoa_f', $where);
        $this->db->update('endereco', $data);
        return true;
    }

}
