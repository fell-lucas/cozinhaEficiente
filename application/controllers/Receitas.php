<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Receitas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('receitas_model');
        $this->load->model('inicio_model');
    }

    public function index() {
        $data['titulo'] = 'Cozinha Eficiente | Receitas';
        $this->template->load('template', 'receitas', $data);
    }

    public function enviar($d) {
        $q = $this->receitas_model->ingredientes($d['ingredientes']);
        $ing = array_column($q, 'nome');
        sort($ing);
        $ingr = implode(",", $ing);
        $data = array(
            'nome' => ucwords($d['nome']),
            'ingredientes' => $ingr,
            'tempodecozimento' => $d['tempocoz'],
            'porcoes' => $d['porcoes'],
            'imagem' => '0',
            'descricao' => $d['descricao']
        );
        $idreceita = $this->receitas_model->set_receita($data);
        $this->receitas_model->updtImagem($idreceita);
        //processamento da imagem
        $filename = explode(".", $_FILES["imagem"]["name"]);
        $extension = end($filename);
        $caminho = FCPATH . 'assets/img/receitas/' . $idreceita . "." . $extension;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);
        //fim processamento da imagem
    }

    public function exibir() {
        $prod_usuario = $this->inicio_model->get_produtos_usuario();
        $p_us['ingredientes_usuario'] = '';
        $i = 0;
        $len = count($prod_usuario);
        if ($prod_usuario !== FALSE) {
            foreach ($prod_usuario as $pu) {
                if ($i >= $len - 1) {
                    $p_us['ingredientes_usuario'] .= ($pu->nomeprod) . '';
                } else {
                    $p_us['ingredientes_usuario'] .= ($pu->nomeprod) . ',';
                }
                $i++;
            }
        }
        $receita = $this->receitas_model->exibir();
        $i = 0;
        $resultreceita = array();
        foreach ($receita as $rec) {
            $ing_us = explode(',', $p_us['ingredientes_usuario']);
            $ing_receita = explode(',', $rec['ingredientes']);
            $check = array_intersect($ing_us, $ing_receita);
            $reindexado = array_values($check);
            if ($reindexado == $ing_receita) {
                $l = 0;
                $tamanho = count($reindexado);
                $ing_final = "";
                foreach ($reindexado as $reind) {
                    if ($l >= $tamanho - 1) {
                        $ing_final .= $reind . '';
                    } else if ($l >= $tamanho - 2) {
                        $ing_final .= $reind . ' e ';
                    } else {
                        $ing_final .= $reind . ', ';
                    }
                    $l++;
                }
                $rec['ingredientes'] = $ing_final;
                $resultreceita[$i] = $rec;
            }
            $i++;
        }
        $a['data'] = array_values($resultreceita);
        echo json_encode($a);
    }

    public function exibir_all() {
        $receita['data'] = $this->receitas_model->exibir();
        echo json_encode($receita);
    }

    public function excluir() {
        if (!empty($this->input->post("registrosexcluir"))) {
            $registrosexcluir = explode(",", $this->input->post("registrosexcluir"));
            if ($this->receitas_model->excluir($registrosexcluir)) {
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

    public function validar() {
        $this->form_validation->set_rules('nome', 'Nome', 'required|min_length[3]');
        if ($this->input->post('ingredientes') == NULL) {
            $this->form_validation->set_rules('ingredientes[]', 'Ingredientes', 'required');
        }
        $this->form_validation->set_rules('tempocoz', 'Tempo de cozimento', 'required');
        $this->form_validation->set_rules('porcoes', 'Porções', 'required');
        if (empty($_FILES['imagem']['name'])) {
            $this->form_validation->set_rules('imagem', 'Imagem', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $erros = array(
                '0' => false,
                '1' => validation_errors(),
            );
            echo json_encode($erros);
        } else {
            $this->enviar($_POST);
            $tipo = 'alert-success';
            $msg = '<strong>Sucesso!</strong> Dados cadastrados no sistema.';
            $dataMSG = array(
                $tipo,
                $msg
            );
            echo json_encode($dataMSG);
        }
    }

    public function exibirExcluir() {
        if (!empty($this->input->get("registrosexcluir"))) {
            $registrosexcluir = explode(",", $this->input->get("registrosexcluir"));
            $resultado = $this->receitas_model->get_nomereceita($registrosexcluir);
            echo json_encode($resultado);
        }
    }

}
