<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $aTipo = false;
        $aMsg = false;
        $conn = new mysqli("127.0.0.1", "root", "", "cEficiente");
        if ($conn->connect_error) {
            die("Erro de conexão: " . $conn->connect_error);
        }
        foreach (array("SET NAMES 'utf8'", 'SET character_set_connection=utf8',
    'SET character_set_client=utf8', 'SET character_set_results=utf8') as $u) {
            $conn->query($u);
        }
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="<?php echo base_url('assets/img/logo.png') ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $titulo ?></title>
        <!-- Bootstrap Css -->
        <link href="<?php echo base_url('vendors/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
        <!-- Animate.css -->
        <link href="<?php echo base_url('vendors/animate/animate.min.css') ?>" rel="stylesheet">
        <!-- Custom Theme Style -->
        <link href="<?php echo base_url('assets/css/custom.min.css') ?>" rel="stylesheet">
        <!-- alerts -->
        <link href="<?php echo base_url('assets/css/alert.css') ?>" rel="stylesheet">
    </head>
    <body class="login">
        <div>
            <a class="hiddenanchor" id="signin"></a>
            <a class="hiddenanchor" id="signup"></a>
            <div class="login_wrapper">
                <div id="register" class="animate form registration_form">
                    <section class="login_content">
                        <form action="?stat=2" method="post">
                            <h1>Cadastro</h1>
                            <div>
                                <input type="text" class="form-control" name="signupName" placeholder="Nome" required />
                            </div>
                            <div>
                                <input type="email" class="form-control" name="signupEmail" placeholder="Email" required />
                            </div>
                            <div>
                                <input type="password" class="form-control" name="signupPassword" placeholder="Senha" required />
                            </div>
                            <div>
                                <button type="submit" class="btn btn-default submit" >Enviar</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="separator">
                                <p class="change_link">Já é um membro?<a href="#signin" class="to_register"> Log in </a></p>
                                <div class="clearfix"></div>
                                <br />
                                <div align="center">
                                    <img class="img-responsive" width="100" height="100" src="<?php echo base_url('assets/img/logo.png') ?>" alt="">
                                    <h1>Cozinha Eficiente</h1>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
                <div class="animate form login_form">
                    <section class="login_content">
                        <form action="?stat=1" method="post">
                            <h1>Login</h1>
                            <div>
                                <input type="email" class="form-control" name="loginEmail" placeholder="Email" required />
                            </div>
                            <div>
                                <input type="password" class="form-control" name="loginPassword" placeholder="Senha" required />
                            </div>
                            <div>
                                <button class="btn btn-default submit">Entrar</button>
                                <button id="admin" class="btn btn-default">Administração</button>
                            </div>
                            <div class="clearfix"></div>
                            <div class="separator">
                                <p class="change_link">Novo no site?<a href="#signup" class="to_register"> Criar conta </a></p>
                                <div class="clearfix"></div>
                                <br />
                                <div align="center">
                                    <img class="img-responsive" width="100" height="100" src="<?php echo base_url('assets/img/logo.png') ?>" alt="">
                                    <h1>Cozinha Eficiente</h1>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
        <?php

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
        /*
         * checa se existe $_GET['stat']
         * stat == 1: login
         * stat == 2: cadastro
         * stat == 3: logout
         */
        if (filter_input(INPUT_GET, 'stat')) {
            //login
            //se existe, checa se é igual a 1 (login) e se existe o $_POST do email
            if (filter_input(INPUT_GET, 'stat') == 1) {
                if (!filter_input(INPUT_POST, 'loginEmail', FILTER_VALIDATE_EMAIL)) {
                    $aTipo = "danger";
                    $aMsg = '<strong>Erro:</strong> O formato do e-mail está incorreto.';
                }
                //checa as credenciais enviadas pelo usuário no formulário de login
                if ($conn->query(select('*', 'usuarios', array(
                            'email' => filter_input(INPUT_POST, 'loginEmail'),
                            'senha' => filter_input(INPUT_POST, 'loginPassword'),
                            'acesso' => '1')
                        ))->num_rows != 1) {
                    $aTipo = "danger";
                    $aMsg = '<strong>Erro:</strong> O login informado não existe.';
                } else {
                    //usuário logado com sucesso, salva os dados na sessão
                    $dadosUsuario = $conn->query(select('*', 'usuarios', array(
                                'email' => filter_input(INPUT_POST, 'loginEmail'),
                            )))->fetch_assoc();
                    $_SESSION['nome'] = $dadosUsuario['nome'];
                    $_SESSION['id'] = $dadosUsuario['id'];
                    $_SESSION['admin'] = false;
                    //redireciona o usuário da tela de login para o dashboard
                    echo '<script>window.location.replace("' . base_url('inicio') . '");</script>';
                }
            } //.login
            //cadastro
            elseif (filter_input(INPUT_GET, 'stat') == 2) {
                //checa se o e-mail está em formato correto
                if (!filter_input(INPUT_POST, 'signupEmail', FILTER_VALIDATE_EMAIL)) {
                    $aTipo = "danger";
                    $aMsg = '<strong>Erro:</strong> O formato do e-mail está incorreto.';
                }
                //checa se o e-mail sendo cadastrado já existe no banco de dados
                if ($conn->query(select('*', 'usuarios', array(
                            'email' => filter_input(INPUT_POST, 'signupEmail'),
                            'acesso' => '1')
                        ))->num_rows != 0) {
                    $aTipo = 'danger';
                    $aMsg = '<strong>Erro:</strong> Já existe alguém com esse endereço de e-mail.';
                }
                //se não existe, checa se os dados do form existem e estão formatados corretamente
                elseif (filter_input(INPUT_POST, 'signupEmail', FILTER_VALIDATE_EMAIL) &&
                        filter_input(INPUT_POST, 'signupName') &&
                        filter_input(INPUT_POST, 'signupPassword')) {
                    //tudo certo, insere os dados cadastrados no banco de dados
                    //md5 para segurança já está ultrapassado, removi
                    if ($conn->query(insert('usuarios', array(
                                "nome" => filter_input(INPUT_POST, 'signupName'),
                                "email" => filter_input(INPUT_POST, 'signupEmail', FILTER_VALIDATE_EMAIL),
                                "senha" => filter_input(INPUT_POST, 'signupPassword'),
                                "acesso" => '1')))) {
                        $aTipo = "success";
                        $aMsg = '<strong>Sucesso!</strong> Sua conta foi cadastrada no sistema.';
                    } else {
                        $aTipo = "danger";
                        $aMsg = '<strong>Erro:</strong> Algo está impedindo seu cadastro.';
                    }
                }
            } //.cadastro
            //checa se o stat == 3 (logout) e se existe dados salvos na sessão, então desloga o usuário  
            elseif (filter_input(INPUT_GET, 'stat') == 3 && isset($_SESSION['nome'])) {
                session_destroy();
                $aTipo = "info";
                $aMsg = '<strong>Aviso:</strong> Você deslogou com sucesso.';
                //stat == 4, permissão negada, nenhum dado salvo na sessão, usuário não cadastrado tentando acesso
            }
            //access block
            elseif (filter_input(INPUT_GET, 'stat') == 4) {
                $aTipo = "danger";
                $aMsg = '<strong>Erro:</strong> Você precisa estar logado para acessar a página.';
            }//.accessblock
        }
        /* Listener para as mensagens de alerta */

        function listener($aTipo, $aMsg) {
            return "<div class='col-sm-offset-2 col-sm-8 alert alert-$aTipo'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'> &times;</a>$aMsg
            </div>";
        }

        if ($aTipo && $aMsg) {
            echo listener($aTipo, $aMsg);
        }
        ?>
        <!-- jQuery -->
        <script src="<?php echo base_url('vendors/jquery/dist/jquery.min.js') ?>"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url('vendors/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
        <!-- Custom Theme Scripts -->
        <script src="<?php echo base_url('assets/js/custom.min.js') ?>"></script>
        <script>
            $('#admin').click(function () {
                event.preventDefault();
                window.location.replace("<?php echo base_url('admin') ?>");
            });
        </script>
    </body>
</html>