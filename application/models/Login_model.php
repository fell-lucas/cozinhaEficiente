<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function select($what, $table, $where = array()) {
        $this->db->select('');
        /* $ret = "SELECT $what FROM $table WHERE";
          $i = 0;
          foreach ($where as $key => $val) {
          $i++;
          if ($i == count($where)) {
          $ret = $ret . " " . $key . " = '" . $val . "'";
          } else {
          $ret = $ret . " " . $key . "= '" . $val . "'" . " and";
          }
          }
          return $ret;
         */
    }

    function insert($table, $what = array()) {
        /* $i = 0;
          $keys = "(";
          $vals = "(";
          foreach ($what as $key => $val) {
          $i++;
          if ($i != count($what)) {
          //nome, email, senha,
          $keys = $keys . "$key, ";
          //'lucas', 'lucasafell@gmail.com', 'bolo',
          $vals = $vals . "'$val', ";
          } else {
          // acesso)
          $keys = $keys . "$key)";
          // '1')
          $vals = $vals . "'$val')";
          }
          }
          $ret = "INSERT INTO $table $keys VALUES $vals";
          return $ret;
         */
    }

}
