<?php

session_start();

if(!isset($_SESSION['userlogin']) || empty($_SESSION['userlogin']) || !isset($_SESSION['userlogin']['user_level']) || empty($_SESSION['userlogin']['user_level']) || $_SESSION['userlogin']['user_level'] != 3):
    die();
endif;
$permission_files = true;
require('../../_app/Config.inc.php');

// Recupera os dados postados pelo jquery! 
$postAjax = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$File = "Rooms";
$json = null;

if(($postAjax && $postAjax['action']) && $postAjax['file'] == $File):
    
    // Obtém a action e coloca o valor dentro de uma variável e limpa os dados postados pelo jquery!
    $action = $postAjax['action'];
    unset($postAjax['action'], $postAjax['file']);
    $postAjax = array_map("strip_tags", $postAjax);
    $postAjax = array_map("trim", $postAjax);
    
    switch($action):    
        
        case "Room_Update":
            $id_room = $postAjax['room_id'];
            unset($postAjax['room_id']);
            if(in_array("", $postAjax)):
                $json['message'] = "<b>ERRO AO ATUALIZAR AMBIENTE: </b>Favor preencha todos os campos!";
            else:
                $read_room = new _app\Conn\Read;
                $read_room->FullRead("SELECT room_id FROM rooms WHERE room_title = :room_title AND room_id != :room_id", "room_title={$postAjax['room_title']}&room_id={$id_room}");
                if(!$read_room->getResult()):
                    $update = new _app\Conn\Update;
                    $update->ExeUpdate("rooms", $postAjax, "WHERE room_id = :room_id", "room_id={$id_room}");
                    if($update->getResult()):
                        $json['message'] = "<b>Tudo Certo: </b> Sala atualizada com sucesso!";
                    else:
                        $json['message'] = "<b>ERRO AO ATUALIZAR SALA: </b> Tente novamente!";
                    endif;                
                else:
                    $json['message'] = "<b>ERRO AO ATUALIZAR SALA: </b> Já existe outra sala com o nome informado!";
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
    die();
endif;