<?php
ob_start();
$permission_files = true;
require('../_app/Config.inc.php');
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="robots" content="noindex, nofollow"/>
        <title>Bem Vindo(a) ao Coffee Control - Coffee Control</title>
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800'/>
        <script src="<?= HOME; ?>/_cdn/js/jquery.js"></script>
        <script src="<?= HOME; ?>/_cdn/js/jquery.form.min.js"></script>
        <script src="<?= HOME; ?>/_cdn/js/jquery.mask.js"></script>
        <script src="<?= HOME; ?>/admin/_js/coffeecontrol.js"></script>
        <style>
            *:before, *:after, *{margin: 0; padding: 0; font-family: 'Open Sans', sans-serif;}
            body.login{background: #F3F3F3;}
            #login{width: 100%; height:100%; position: absolute; display: flex;}
            #login .boxin{width:330px; margin: auto; background: #FFF; -webkit-box-shadow:0 0 5px 0 #ccc; -moz-box-shadow:0 0 5px 0 #ccc; box-shadow:0 0 5px 0 #ccc;}
            #login .boxin{padding: 20px;}
            #login .boxin h1{font-size: 30px; text-transform: uppercase; font-weight: 600; color: #09f; border-bottom: 5px solid #09f;}
            #login .boxin h1{margin-bottom: 15px; padding-bottom: 5px; text-align: center;}
            #login form label{display: block; margin-bottom: 15px;}
            #login form label span{display: block; font-weight: 300;}
            #login form label input{width:92%; max-width: 310px; padding: 10px; margin-top: 7px; font-size: 16px;}
            #login form .btn{margin-top: 10px;}
            
            .Error{position: fixed; top:50px; right: 0; margin-right: 2%; cursor: pointer;}
            .trigger{padding: 20px; text-align: center; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;}
            .accept{background: #71ca73;}
            .infor{background:  #c8dbfd;}
            .error{background:  #faf7b7;}
            .alert{background:  #ffafaf;}
            
            .bg-green{background-color:#048C76; color:#FFF;}
            
            .btn{padding: 4% 6%; border: 3px solid #eee; background: #fbfbfb; text-transform: uppercase; font-weight: bold; cursor: pointer; font-size: 0.8em; color: #fff;}
            .btn-green{background-color:#398e2d; border-color:#FFF;}
            .btn-green:hover{background-color:#245b1d; border-color:#FFF;}
            .btn-red{background-color:#ff3232; border-color:#FFF;}
            .btn-red:hover{background-color:#d40a0a; border-color:#FFF;}
            .btn-orange{background-color:#F26829; border-color:#FFF;}
            .btn-orange:hover{background-color:#DA411B; border-color:#FFF;}
            .btn-blue{background-color:#3395C4; border-color:#FFF;}
            .btn-blue:hover{background-color:blue; border-color:#FFF;}
            .radius{-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;}
            .ds-none{display:none;}
            .ajax_load{float:left; margin-top: 17px; margin-left: 13px;}
            .return-ajax{position: fixed; top:50px; right: 0; margin-right: 2%; cursor: pointer;}
        </style>
    </head>
    <body class="login">
        <div id="login">
            <div class="boxin">
                <?php
                
                if(!empty($_SESSION['userlogin']) && !empty($_SESSION['userlogin']['user_level']) && $_SESSION['userlogin']['user_level'] === '3'){
                    header('location:painel.php?cc=home');
                }
                
                $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
                $get = filter_input(INPUT_GET,'cc', FILTER_DEFAULT);
                
                if(!empty($get)){
                    if($get == 'restrito' && empty($dataLogin['AdminLogin'])){
                        echo "<div class='Error' style='cursor:pointer;'>" . WSErro("<b>OOPPPSS:</b> Acesso negado. Favor efetue o login para acessar o painel!", WS_INFOR) . "</div>";
                    }
                    elseif($get == 'logoff' && empty($dataLogin['AdminLogin'])){
                        echo "<div class='Error' style='cursor:pointer;'>" . WSErro("<b>TUDO CERTO:</b> Sucesso ao Sair do Painel, Volte Sempre!", WS_INFOR) . "</div>";
                    }
                }
                
                ?>

                <form name="LoginForm" action="" method="post">
                    <div class="return-ajax ds-none"></div>
                    <input type="hidden" name="file" value="Login"/>
                    <input type="hidden" name="action" value="Admin_Login"/>
                    <label>
                        <span>Seu E-mail:</span>
                        <input type="email" title="Informe Seu Email" placeholder="Informe Seu Email" name="user_email" required/>
                    </label>
                    <label>
                        <span>Sua Senha:</span>
                        <input type="password" title="Informe Sua Senha" name="user_password" placeholder="Informe Sua Senha"/>
                    </label>
                    <input style="float:left;" type="submit" name="AdminLogin" value="Logar" title="Entrar no Painel" class="btn btn-blue radius"/>
                    <img class="ds-none ajax_load" src="images/load.gif" title="Carregando..." alt="Carregando..."/>
                </form>
            </div>
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>