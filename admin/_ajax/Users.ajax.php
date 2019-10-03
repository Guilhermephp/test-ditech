<?php

session_start();

if(!isset($_SESSION['userlogin']) || empty($_SESSION['userlogin']) || !isset($_SESSION['userlogin']['user_level']) || empty($_SESSION['userlogin']['user_level']) || $_SESSION['userlogin']['user_level'] != 3):
    header("Location:https://www.colortek.com.br/404");
endif;
$permission_files = true;
require('../../_app/Config.inc.php');

// Recupera os dados postados pelo jquery! 
$postAjax = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = "Users";
$json = null;

if(($postAjax && $postAjax['action']) && $postAjax['file'] == $File):
    
    // Obtém a action e coloca o valor dentro de uma variável e limpa os dados postados pelo jquery!
    $action = $postAjax['action'];
    unset($postAjax['action'], $postAjax['file']);
    
    switch($action):    
        
        // Instância a classe e atualiza o usuário de acordo com o id postado e retorna uma mensagem!
        case "Users_Update": 
            $id = $postAjax['user_id'];
            unset($postAjax['user_id']);
            require("../_models/AdminUser.class.php");
            $postUpdate = new AdminUser;
            $postUpdate->ExeUpdate($id, $postAjax);
            if($postUpdate->getResult()):
                $json['message'] = "<b>TUDO CERTO</b>: Usuário Foi Atualizado com Sucesso!";
            else:
                $json['message'] = $postUpdate->getError()[0];
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
    header("Location:https://www.colortek.com.br/404");
endif;