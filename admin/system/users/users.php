<?php
if(!class_exists('_app\Models\Login')):
    die();
endif;
?>

<div style="border:2px solid #eee;" class="container">
    <div class="container padding20 bg-write breadcrumbs">
        <div class="box box-large breadcrumbs-box last">
            <h1 style="margin-top:6px; margin-left: 30px; margin-bottom: 10px;" class="bg-write font300">Usuários</h1>
            <p class="fontsizeb font400">>> Coffee Control / Dashboard / Usuários <<</p>
        </div>    
        <div class="form-search fl-right">
            <form class="container no_post_ajax" method="post" action="">
                <input style="width:350px; margin-top: 10px; margin-right: 8px;" type="text" name="s" placeholder="Pesquisar Usuário" required/>
                <button style="position: relative; bottom: 4px;" class="btn btn-green search" title="Pesquisar Usuário" type="submit" name="SendSearch" value="search"></button>
            </form>
        </div> 
    </div>    
</div>   

<div class="content form_create usuarios">

    <section class="container">

        <?php
        $postSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if(!empty($postSearch) && isset($postSearch['SendSearch'])):
            unset($postSearch['SendSearch']);
            $postSearch = array_map('strip_tags', $postSearch);
            $postSearch = array_map('trim', $postSearch);
            $post = $postSearch['s'];
            $search = urlencode($postSearch['s']);
            if(in_array('', $postSearch)):
                echo WSErro("<b>ERRO AO PESQUISAR:</b> <span class='font600'>Favor Preencha o Campo de Pesquisa Corretamente!</span>", 'Error bg-green');
            else:
                header("location:painel.php?cc=users/search&s=$search");
            endif;
        endif;

        $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);

        if(!empty($delete)){
            $delete = strip_tags(trim($delete));
            if(!is_numeric($delete)){
                header("Location:painel.php?cc=users/users");
            }
            else{
                $read = new _app\Conn\Read;
                $read->ExeRead("ws_users", "WHERE user_id = :id", "id={$delete}");
                if(!$read->getResult()):
                    header("Location:painel.php?cc=users/users");
                endif; 
                require('_models/AdminUser.class.php');
                $deleteUser = new AdminUser;
                $deleteUser->ExeDelete($delete);

                header("Location:painel.php?cc=users/users");
            }
        }

        $u = 0;
        $readUsers = new _app\Conn\Read;
        $readUsers->ExeRead('ws_users');
        if($readUsers->getResult()){
            foreach($readUsers->getResult() as $users){
                extract($users);
                $u++;
                ?>  
                <article class="users bottom30 padding20 bg-write box box-medium <?php
                if ($u % 3 === 0): echo "last";
                endif;
                ?>">
                    <ul>    
                        <li class="bottom5 ds-block"><span class="fontsizeb"><?= $user_name . " " . $user_lastname; ?></span></li>
                        <li class="bottom5 ds-block"><span class="fontsizeb">Desde <?= date('d/m/Y', strtotime($user_registration)); ?> as <?= date('H:i', strtotime($user_registration)); ?></span></li>
                        <li class="bottom5 ds-block"><span class="fontsizeb"><?php
                                if ($user_level == 1) {
                                    echo 'CLIENTE NOVO<p>(Usuário)</p>';
                                } elseif ($user_level == 2) {
                                    echo 'Editor';
                                } else {
                                    echo 'SUPER ADMIN<p>(Admin)</p>';
                                }
                                ?></span></li>
                        <li class="ds-inblock"><a class="act_edit btn btn-green fontzero" href="painel.php?cc=users/create&id=<?= $user_id; ?>" title="Editar">Editar</a></li>
                        <li class="ds-inblock"><a class="act_delete btn btn-red fontzero" href="painel.php?cc=users/users&delete=<?= $user_id; ?>" title="Deletar">Deletar</a></li>
                    </ul>
                </article>
                <?php
            }
        } 
        else{
            echo WSErro("Desculpe, ainda não existem usuários cadastradas no seu sistema!", 'bg-green');
        }
        ?>

    </section>  

</div> <!-- content home -->