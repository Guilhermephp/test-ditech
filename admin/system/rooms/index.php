<?php
if(!class_exists('_app\Models\Login')):
    die();
endif;
?>

<div style="border:2px solid #eee;" class="container">
    <div class="container padding20 bg-write breadcrumbs">
        <div class="box box-large breadcrumbs-box last">
            <h1 style="margin-top:6px; margin-left: 30px; margin-bottom: 10px;" class="bg-write font300">Salas</h1>
            <p class="fontsizeb font400">>> Coffee Control / Dashboard / Salas <<</p>
        </div>    
    </div>    
</div>   

<div class="content form_create usuarios">

    <section class="container">

        <?php
            $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);

            if(!empty($delete)){
                $delete = strip_tags(trim($delete));
                if(!is_numeric($delete)){
                    header("Location:painel.php?cc=rooms/index");
                }
                else{
                    $read = new _app\Conn\Read;
                    $read->ExeRead("rooms", "WHERE room_id = :id", "id={$delete}");
                    if(!$read->getResult()):
                        header("Location:painel.php?cc=rooms/index");
                    endif; 

                    $read_room_reserved = new _app\Conn\Read;
                    $read_room_reserved->FullRead("SELECT rooms_users_id FROM rooms_users WHERE room_user_id = :room_user_id", "room_user_id={$delete}");
                    if($read_room_reserved->getResult()){
                        echo ErrorFixed("Erro ao deletar essa sala: Ela está ocupada em algum periodo!");
                    }
                    else{     
                        $deleteRoom = new _app\Conn\Delete;
                        $deleteRoom->ExeDelete("rooms", "WHERE room_id = :id", "id={$delete}");

                        if($deleteRoom->getResult()){
                            header("Location:painel.php?cc=rooms/index");
                        }
                    }
                }
            }

            $u = 0;
            $readRooms = new _app\Conn\Read;
            $readRooms->ExeRead('rooms');
            if($readRooms->getResult()){
                foreach($readRooms->getResult() as $rooms){
                    extract($rooms);
                    $u++;
        ?>  
                    <article class="users bottom30 padding20 bg-write box box-medium<?= ($u % 3 === 0) ? ' last' : ''; ?>">
                        <ul>    
                            <li class="bottom10 ds-block fontsize1b font300">Nome: <span class="font500"><?= $room_title; ?></span></li>
                            <li class="ds-block bottom5"><a class="btn btn-green" href="painel.php?cc=rooms/create&id=<?= $room_id; ?>" title="Editar">Editar</a></li>
                            <li class="ds-block bottom5"><a class="btn btn-red delete" href="painel.php?cc=rooms/index&delete=<?= $room_id; ?>" title="Deletar">Deletar</a></li>
                            <li class="ds-block"><a class="btn btn-orange" href="painel.php?cc=rooms/no_reserve&id=<?= $room_id; ?>" title="Retirar Reserva">Retirar Reserva</a></li>
                        </ul>
                    </article>
        <?php
                }
            } 
            else{
                echo WSErro("Desculpe, ainda não existem salas cadastradas no seu sistema!", 'bg-green');
            }
        ?>
    </section>  
</div> <!-- content home -->