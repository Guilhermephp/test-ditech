<?php

session_start();

if(!isset($_SESSION['userlogin']) || empty($_SESSION['userlogin']) || !isset($_SESSION['userlogin']['user_level']) || empty($_SESSION['userlogin']['user_level']) || $_SESSION['userlogin']['user_level'] != 3):
    die();
endif;
$permission_files = true;
require('../../_app/Config.inc.php');

// Recupera os dados postados pelo jquery! 
$postAjax = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = "Reserves";
$json = null;

if(($postAjax && $postAjax['action']) && $postAjax['file'] == $File):
    
    // Obtém a action e coloca o valor dentro de uma variável e limpa os dados postados pelo jquery!
    $action = $postAjax['action'];
    unset($postAjax['action'], $postAjax['file']);
    $postAjax = array_map("strip_tags", $postAjax);
    $postAjax = array_map("trim", $postAjax);
    
    switch($action):    
        
        case "Reserve_Room":
            if(in_array("", $postAjax)):
                $json['message'] = "<b>Erro ao reservar sala: </b>Favor preencha todos os campos!";
            else:
                $datetime = new datetime; 
                $read_time = new _app\Conn\Read;
                $read_time->FullRead("SELECT date_value FROM dates WHERE date_id = :date_id", "date_id={$postAjax['date_room_id']}");
                if($read_time->getResult()){
                    $postAjax['user_room_id'] = $_SESSION['userlogin']['user_id'];
                    $postAjax['room_user_reserved_date'] = _app\Helpers\Check::DateFormat($postAjax['room_date']) . " " . $read_time->getResult()[0]['date_value'];
                    unset($postAjax['room_date']);
                    $read_room_time = new _app\Conn\Read;
                    $read_room_time->FullRead("SELECT rooms_users_id FROM rooms_users WHERE date_room_id = :date_room_id AND user_room_id = :user_room_id", "date_room_id={$postAjax['date_room_id']}&user_room_id={$_SESSION['userlogin']['user_id']}");
                    if(!$read_room_time->getResult()){
                        $read_date = new _app\Conn\Read;
                        $read_date->FullRead("SELECT rooms_users_id FROM rooms_users WHERE date_room_id = :date_room_id AND room_user_id = :room_user_id", "date_room_id={$postAjax['date_room_id']}&room_user_id={$postAjax['room_user_id']}");
                        if(!$read_date->getResult()){
                            $create_date_reserved = new _app\Conn\Create;
                            $create_date_reserved->ExeCreate("rooms_users", $postAjax);
                            $json['message'] = "Tudo Certo! Sala reservada com sucesso!";
                        }
                        else{
                            $json['message'] = "Oooopssss! Outro usuário já reservou essa sala nesse periodo!";
                        }
                    }
                    else{
                        $json['message'] = "Oooopssss! Você já reservou uma sala nesse periodo!";
                    }    
                }   
                else{
                    $json['message'] = "<b>Erro!</b>";
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