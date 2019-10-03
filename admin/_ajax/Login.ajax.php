<?php

session_start();
$permission_files = true;
require('../../_app/Config.inc.php');

// Recupera os dados postados pelo jquery! 
$postAjax = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = "Login";
$json = null;

if(($postAjax && $postAjax['action']) && $postAjax['file'] == $File):
    
    // Obtém a action e coloca o valor dentro de uma variável e limpa os dados postados pelo jquery!
    $action = $postAjax['action'];
    unset($postAjax['action'], $postAjax['file']);
    $postAjax = array_map("strip_tags", $postAjax);
    $postAjax = array_map("trim", $postAjax);
    
    switch($action):    
        case 'Admin_Login':
            if(in_array('', $postAjax)):
                $json['message'] = "<b>OOOPPPSSS: </b>Informe Seu Email e Senha Para Entrar no Painel!";
            elseif(!_app\Helpers\Check::Email($postAjax['user_email']) || !filter_var($postAjax['user_email'], FILTER_VALIDATE_EMAIL)):
                $json['message'] = "<b>OOOPPPSSS: </b>Email Informado Não é Correto!";
            elseif(strlen($postAjax['user_password']) < 5):
                $json['message'] = "<b>OOOPPPSSS: </b>Senha Informada Não é Correta! Utilize Pelo Menos 5 Caracteres!";
            else:
                $postAjax['user_password'] = md5($postAjax['user_password']);
                $read = new _app\Conn\Read;
                $read->FullRead("SELECT * FROM ws_users WHERE user_email = :email AND user_password = :pass AND user_level = :lv", "email={$postAjax['user_email']}&pass={$postAjax['user_password']}&lv=3");
                if($read->getResult()):
                    $_SESSION['userlogin'] = $read->getResult()[0];
                    $json['message'] = "Olá <b>{$_SESSION['userlogin']['user_name']}</b>, Seja Bem Vindo! Redirecionando para o Painel...";
                    $json['redirect'] = "./";
                else:
                    $json['message'] = "<b>OOOPPPSSS: </b>Não Existe Administrador Com o Email Informado!";
                endif;    
            endif;
            break;
        
        // Retorna uma mensagem de erro, caso a ação postada pelo jquery, não seja válida!    
        default:
            $json['message'] = "<b>Açao não encontrada pelo sistema!</b>";
        break;
        
    endswitch;
    
    // Codifica em objeto json para retornar ao jquery e manipular o mesmo!
    if($json):
        echo json_encode($json);
    endif;
else:
    // Caso entre diretamente nessa página, ou caso não filtre dados postados para esse arquivo, da um die() para parar o programa!
    //die("<h1>Acesso Não Permitido ou Apenas para ADMINS!</h1>");
    header("Location:http://www.colortek.com.br/404");
endif;