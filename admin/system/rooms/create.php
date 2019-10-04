<?php
if(!class_exists('_app\Models\Login')):
    die();
endif;
?>
<div style="border:2px solid #eee;" class="container">
    <div class="container padding20 bg-write breadcrumbs">
        <div class="box box-large">
            <h1 style="margin-top:6px; color: #555; margin-left: 40px; margin-bottom: 10px;" class="bg-write font300 fontsize1b">Cadastrar Usuário</h1>
            <p class="fontsizeb font400">>> Coffee Control / Dashboard / Cadastrar Nova Sala <<</p>
        </div>
        <div class="box box-large last al-center">
            <a style="margin-top: 15px;" href="painel.php?cc=users/create" title="Cadastrar Nova Sala" class="btn btn-green ds-inblock font700 fontsizeb">Cadastrar Sala</a>
        </div>
    </div>    
</div> 
<div class="content form_create">
    <article class="container">
        <?php
        
        $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);
        
        if(!empty($id)):
            $id = strip_tags(trim($id));
            if(!is_numeric($id)){
                header("Location: painel.php?cc=rooms/index"); 
            }
            else{
                $readRomm = new _app\Conn\Read;
                $readRomm->ExeRead("rooms", "WHERE room_id = :id", "id={$id}");
                if($readRomm->getResult()):
                    extract($readRomm->getResult()[0]);
                else:
                    header("Location: painel.php?cc=rooms/index");     
                endif;
            }
        else:
            $post = ['room_date' => date('Y-m-d H:i:s')];
            $create = new _app\Conn\Create;
            $create->ExeCreate("rooms", $post);
            if($create->getResult()):
                header("Location: painel.php?cc=rooms/create&id=" . $create->getResult());     
            endif;
        endif;
        
        ?>
        
        <form method ="post" name="UserCreateForm">
            
            <div class="return-ajax ds-none"></div>
            <input type="hidden" name="file" value="Rooms"/>
            <input type="hidden" name="action" value="Room_Update"/>
            <input type="hidden" name="room_id" value="<?= $id; ?>"/>
            
        <div style="width:90%; margin: 0 5%;" class="box bg-write padding20">    
            
            <?php 
                $datetime = new datetime;
            ?>

            <label class="label">
                <span class="field">Nome:</span>
                <input type="text" name="room_title" title="Informe o nome da sala" placeholder="Informe o nome da sala" value="<?= (isset($room_title) ? $room_title : ''); ?>" required/>
            </label>

            <?php 
                $read_dates_rooms = new _app\Conn\Read;
                $read_dates_rooms->FullRead("SELECT date_room_id FROM rooms_users WHERE user_room_id = :user_room_id AND room_user_id = :room_user_id ORDER BY room_user_id ASC", "user_room_id={$_SESSION['userlogin']['user_id']}&room_user_id={$id}");
                if($read_dates_rooms->getResult()){
                    foreach($read_dates_rooms->getResult() as $dates_rooms){
                        $array_date[] = $dates_rooms['date_room_id'];
                    }
                    $array_date_implode = implode(", ", $array_date);    
                }
            ?>    

            <label class="label">
                <span style="margin-bottom: 15px;" class="ds-block field">Horários:</span>
                    <?php
                        $read_dates = new _app\Conn\Read;
                        $read_dates->FullRead("SELECT * FROM dates WHERE date_id IN ({$array_date_implode}) ORDER BY date_id ASC");
                        if($read_dates->getResult()){
                            foreach($read_dates->getResult() as $read_dates){
                    ?>
                                <p class="red">
                                    <?php echo $datetime->format($read_dates['date_value']); ?> - Ocupado   
                                </p>
                    <?php
                            }
                        }
                        $read_dates = new _app\Conn\Read;
                        $read_dates->FullRead("SELECT * FROM dates WHERE date_id NOT IN ({$array_date_implode}) ORDER BY date_id ASC");
                        if($read_dates->getResult()){
                            foreach($read_dates->getResult() as $key => $dates){
                    ?>
                                <p>
                                    <?php echo $datetime->format($dates['date_value']); ?>   
                                </p>
                    <?php           
                            }
                        }
                    ?>   
            </label>

        </div>
        <div style="margin-top: 20px; width: 90%; margin: 0 5%;" class="bg-write fl-right padding20 al-center">
            <button type="submit" title="Atualizar Sala" class="btn btn-blue" value="Atualizar Sala" name="SendPostForm">Atualizar Sala</button>
            <img style="margin-left: 10px;" class="ajax_load" src="images/load.gif" title="Carregando..." alt="Carregando..."/>
        </div>      
    </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->