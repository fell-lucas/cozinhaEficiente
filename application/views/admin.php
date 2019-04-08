<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $conn = new mysqli("127.0.0.1", "root", "", "cEficiente");
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }
        $conn->query("SET NAMES 'utf8'");
        $conn->query('SET character_set_connection=utf8');
        $conn->query('SET character_set_client=utf8');
        $conn->query('SET character_set_results=utf8');
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/img/logo.png') ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $titulo ?></title>
        <!-- Bootstrap Cs -->
        <link href="<?php echo base_url('vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assets/css/custom.min.css') ?>" rel="stylesheet">
        <!-- alerts -->
        <link href="<?php echo base_url('assets/css/alert.css') ?>" rel="stylesheet">
    </head>
    <body class="login">
        <div>
            <div class="login_wrapper">
                <div class="form login_form">
                    <section class="login_content">
                        <form action="?stat=1" method="post">
                            <h1>Administração</h1>
                            <div>
                                <input type="email" class="form-control" name="loginEmail" placeholder="Email" required />
                            </div>
                            <div>
                                <input type="password" class="form-control" name="loginPassword" placeholder="Senha" required />
                            </div>
                            <div>
                                <button class="btn btn-default submit">Entrar</button>
                                <button id="user" class="btn btn-default">Usuário normal</button>
                            </div>

                            <div class="clearfix"></div>

                            <div class="separator">

                                <div class="clearfix"></div>
                                <br />

                                <div align="center">
                                    <img class="img-responsive" width="100" height="100" src="<?php echo base_url('assets/img/logo.png') ?>" alt="">
                                    <h1>Cozinha Eficiente</h1>
                                    <p>©2019 Todos os direitos reservados.</p>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
            <?php

            function insert($table, $what = array()) {
                $i = 0;
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
            }

            function select($what, $table, $where = array()) {
                $ret = "SELECT $what FROM $table WHERE";
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
            }

            if (filter_input(INPUT_GET, 'stat')) {
                if (filter_input(INPUT_GET, 'stat') == 1) {
                    if (!filter_input(INPUT_POST, 'loginEmail', FILTER_VALIDATE_EMAIL)) {
                        $aTipo = "danger";
                        $aMsg = '<strong>Erro:</strong> O email digitado está em formato incorreto.';
                    }
                    if ($conn->query(select('*', 'usuarios', array(
                                'email' => filter_input(INPUT_POST, 'loginEmail'),
                                'senha' => filter_input(INPUT_POST, 'loginPassword'),
                                'acesso' => '2'
                            )))->num_rows != 1) {
                        $aTipo = "danger";
                        $aMsg = '<strong>Erro:</strong> O login informado não existe.';
                    } else {
                        //usuário logado com sucesso, salva os dados na sessão
                        $dadosUser = $conn->query(select('*', 'usuarios', array(
                                    'email' => filter_input(INPUT_POST, 'loginEmail'),
                                )))->fetch_assoc();
                        $_SESSION['nome'] = $dadosUser['nome'];
                        $_SESSION['id'] = $dadosUser['id'];
                        //define a posição de administrador
                        $_SESSION['admin'] = true;
                        //redireciona o usuário para o dashboard
                        echo '<script>window.location.replace("' . base_url('inicio') . '");</script>';
                    }
                } elseif (filter_input(INPUT_GET, 'stat') == 3 && $_SESSION['nome']) {
                    session_destroy();
                    $aTipo = "info";
                    $aMsg = '<strong>Aviso:</strong> Você deslogou com sucesso.';
                } elseif (filter_input(INPUT_GET, 'stat') == 4) {
                    $aTipo = "danger";
                    $aMsg = '<strong>Erro:</strong> Você precisa estar logado para acessar a página.';
                }
            }
            ?>
        </div>
        <?php
        /* Listener para as mensagens de alerta */
        if (isset($aTipo) && isset($aMsg)) {
            echo "<div class='col-sm-offset-2 col-sm-8 alert alert-$aTipo'>
            <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>$aMsg
         </div>";
        }
        ?>
        <!-- jQuery -->
        <script src="<?php echo base_url('vendors/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url('vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <!-- Custom Theme Scripts -->
        <script src="<?php echo base_url('assets/js/custom.min.js') ?>"></script>
        <script>
            $('#user').click(function () {
                event.preventDefault();
                window.location.replace("<?php echo base_url() ?>");
            });
        </script>
    </body>
</html>