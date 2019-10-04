<?php

session_start();

if(!isset($_SESSION['userlogin']) || empty($_SESSION['userlogin']) || !isset($_SESSION['userlogin']['user_level']) || empty($_SESSION['userlogin']['user_level']) || $_SESSION['userlogin']['user_level'] != 3):
    die();
endif;
$permission_files = true;
require('../../_app/Config.inc.php');

// Recupera os dados postados pelo jquery! 
$postAjax = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = "No_Reserves";
$json = null;

if(($postAjax && $postAjax['action']) && $postAjax['file'] == $File):
    
    // Obtém a action e coloca o valor dentro de uma variável e limpa os dados postados pelo jquery!
    $action = $postAjax['action'];
    unset($postAjax['action'], $postAjax['file']);
    $postAjax = array_map("strip_tags", $postAjax);
    $postAjax = array_map("trim", $postAjax);
    
    switch($action):    
        
        case "No_Reserve_Room":
            if(in_array("", $postAjax)):
                $json['message'] = "<b>Erro ao retirar reserva de sala: </b>Favor preencha todos os campos!";
            else:
                $read_date_and_room_user = new _app\Conn\Read;
                $read_date_and_room_user->FullRead("SELECT rooms_users_id, date_room_id FROM rooms_users WHERE date_room_id = :date_room_id AND room_user_id = :room_user_id AND user_room_id = :user_room_id", "date_room_id={$postAjax['date_room_id']}&room_user_id={$postAjax['room_user_id']}&user_room_id={$_SESSION['userlogin']['user_id']}");
                if($read_date_and_room_user->getResult()){
                    $delete_date_and_room_user = new _app\Conn\Delete;
                    $delete_date_and_room_user->ExeDelete("rooms_users", "WHERE rooms_users_id = :rooms_users_id", "rooms_users_id={$read_date_and_room_user->getResult()[0]['rooms_users_id']}");
                    $json['id_date'] = $read_date_and_room_user->getResult()[0]['date_room_id'];
                    $json['message'] = "Tudo Certo! Reserva de sala retirada com sucesso!";
                }   
                else{
                    $json['message'] = "<b>Erro ao retirar reserva de sala: </b>Tente novamente!";
                }
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
    die();
endif;