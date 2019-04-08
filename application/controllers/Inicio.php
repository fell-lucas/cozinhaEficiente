<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('inicio_model');
        $this->load->model('categoria_model');
    }

    public function index() {
        $data['titulo'] = 'Cozinha Eficiente | Início';
        $this->template->load('template', 'inicio', $data);
    }

    public function exibirNome() {
        if (!empty($this->input->get("q"))) {
            $resultado = $this->inicio_model->get_produtos();
            echo $resultado;
        }
    }

    public function exibirCategoria() {
        if (!empty($this->input->get("q"))) {
            $resultado = $this->categoria_model->get_categoria();
            echo $resultado;
        }
    }

    public function exibirTodos() {
        $res = $this->inicio_model->get_produtos_usuario();
        if ($res !== FALSE) {
            foreach ($res as $r) {
                if ($r->validade == date("d/m/Y")) {
                    $this->inicio_model->vencido($r->id, date("d/m/Y"));
                }
                if ($r->quantidade == 0){
                    $this->inicio_model->excluir($r->id);
                }
            }
            $resultado = $this->inicio_model->get_produtos_usuario();
        } else {
            $resultado = array();
        }
        $a['data'] = $resultado;

        echo json_encode($a);
    }

    public function exibirExcluir() {
        if (!empty($this->input->get("registrosexcluir"))) {
            $registrosexcluir = explode(",", $this->input->get("registrosexcluir"));
            $resultado = $this->inicio_model->get_produtos_porid($registrosexcluir);
            echo json_encode($resultado);
        }
    }

    public function cadastrar($d) {
        $data = array(
            'id_produto' => ucwords($d['nome']),
            'compra' => $d['compra'],
            'validade' => $d['validade'],
            'quantidade' => $d['quantidade'],
            'id_usuario' => $_SESSION['id']
        );

        if ($this->inicio_model->get_produtos_usuario_idprod($this->input->post('nome')) > 0) {
            // se já existe o produto na lista, ele não procede
            return false;
        } else {
            $this->inicio_model->set_comida($data);
            return true;
        }
    }

    public function cadastraradm() {
        if ($this->input->post('acao') == 'cadastrar') {
            $data = array(
                'nome' => ucwords($this->input->post('nomeProdadm')),
                'id_categoria' => $this->input->post('catProdadm')
            );

            if ($this->inicio_model->get_produtos($this->input->post('nome')) > 0) {
                $tipo = 'alert-danger';
                $msg = '<strong>Erro!</strong> Este produto já existe no sistema.';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
            } else {
                $this->inicio_model->set_comida_adm($data);
                $tipo = 'alert-success';
                $msg = '<strong>Sucesso!</strong> Dados inseridos no sistema.';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
            }
        } else {
            $tipo = 'alert-danger';
            $msg = '<strong>Erro!</strong> Não foi possível cadastrar os dados no sistema.';
            $dataMSG = array(
                $tipo,
                $msg
            );
        }
        echo json_encode($dataMSG);
    }

    public function excluir() {
        if (!empty($this->input->post("registrosexcluir"))) {
            $registrosexcluir = explode(",", $this->input->post("registrosexcluir"));
            if ($this->inicio_model->excluir($registrosexcluir)) {
                $tipo = 'alert-success';
                $msg = '<strong>Sucesso!</strong> Item(ns) excluído(s) com excelência.';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
                echo json_encode($dataMSG);
            } else {
                $tipo = 'alert-danger';
                $msg = '<strong>Erro!</strong> O(s) item(ns) não foi(ram) excluído(s).';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
                echo json_encode($dataMSG);
            }
        }
    }
    
    public function consumir($id) {
        if ($this->inicio_model->consumir($id)) {
            return true;
        } else {
            return false;
        };
    }

    public function validar() {
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required');
        $this->form_validation->set_rules('compra', 'Data de compra', 'required|callback_diadehoje');
        $this->form_validation->set_rules('validade', 'Data de validade', 'required');
        if ($this->form_validation->run() == FALSE) {
            $erros = array(
                '0' => false,
                '1' => validation_errors(),
            );
            echo json_encode($erros);
        } else {
            if ($this->cadastrar($_POST)) {
                $tipo = 'alert-success';
                $msg = '<strong>Sucesso!</strong> Dados cadastrados no sistema.';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
            } else {
                $tipo = 'alert-danger';
                $msg = '<strong>Erro!</strong> Já existe esse produto na sua lista.';
                $dataMSG = array(
                    $tipo,
                    $msg
                );
            }
            echo json_encode($dataMSG);
        }
    }
    //callback para o post da data de compra
    public function diadehoje($str) {
        $dc = str_replace('/', '-', $str);
        if (strtotime($dc) > strtotime(date("d-m-Y")) OR strtotime($dc) == false) {
            $this->form_validation->set_message('diadehoje', 'O campo {field} é obrigatório e não pode ser maior que hoje.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
