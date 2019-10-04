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
    </div>    
</div> 
<div class="content form_create">
    <article class="container">
        
        <form method ="post" name="UserCreateForm">
            
            <div class="return-ajax ds-none"></div>
            <input type="hidden" name="file" value="Reserves"/>
            <input type="hidden" name="action" value="Reserve_Room"/>
            
        <div style="width:90%; margin: 0 5%;" class="box bg-write padding20">    

            <?php 
                $datetime = new datetime;
            ?>
            <label class="label">
                <span class="field">Data:</span>
                <input class="date" type="text" name="room_date" title="Informe a data da sala" placeholder="Informe a data da sala" value="<?= $datetime->format("d/m/Y"); ?>" required/>
            </label>

            <label class="label">
                <span class="field">Horários:</span>
                <select name="date_room_id">
                    <option value="">Selecionar Horário</option>
                    <?php 
                        $read_dates = new _app\Conn\Read;
                        $read_dates->FullRead("SELECT * FROM dates");
                        if($read_dates->getResult()){
                            foreach($read_dates->getResult() as $dates){
                    ?>
                                <option value="<?php echo $dates['date_id']; ?>">
                                    <?php echo $datetime->format($dates['date_value']); ?>   
                                </option>
                    <?php   
                            }     
                        }
                    ?>
                </select>      
            </label>

            <label class="label">
                <span class="field">Sala:</span>
                <select name="room_user_id">
                    <option value="">Selecionar Sala</option>
                    <?php 
                        $read_rooms = new _app\Conn\Read;
                        $read_rooms->FullRead("SELECT * FROM rooms");
                        if($read_rooms->getResult()){
                            foreach($read_rooms->getResult() as $rooms){
                    ?>
                                <option value="<?php echo $rooms['room_id']; ?>"><?php echo $rooms['room_title']; ?></option>
                    <?php   
                            }     
                        }
                    ?>
                </select>      
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