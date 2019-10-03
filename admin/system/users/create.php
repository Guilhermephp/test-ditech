<?php
if(!class_exists('_app\Models\Login')):
    die();
endif;
?>
<div style="border:2px solid #eee;" class="container">
    <div class="container padding20 bg-write breadcrumbs">
        <div class="box box-large">
            <h1 style="margin-top:6px; color: #555; margin-left: 40px; margin-bottom: 10px;" class="bg-write font300 fontsize1b">Cadastrar Usuário</h1>
            <p class="fontsizeb font400">>> Coffee Control / Dashboard / Cadastrar Novo Usuario <<</p>
        </div>
        <div class="box box-large last al-center">
            <a style="margin-top: 15px;" href="painel.php?cc=users/create" title="Cadastrar Novo Usuário" class="btn btn-green ds-inblock font700 fontsizeb">Cadastrar Usuário</a>
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
                header("Location: painel.php?cc=users/users"); 
            }
            else{
                $readUser = new _app\Conn\Read;
                $readUser->ExeRead("ws_users", "WHERE user_id = :id", "id={$id}");
                if($readUser->getResult()):
                    extract($readUser->getResult()[0]);
                    unset($user_password);
                else:
                    header("Location: painel.php?cc=users/users");     
                endif;
            }
        else:
            $post = ['user_registration' => date('Y-m-d H:i:s')];
            $create = new _app\Conn\Create;
            $create->ExeCreate("ws_users", $post);
            if($create->getResult()):
                header("Location: painel.php?cc=users/create&id=" . $create->getResult());     
            endif;
        endif;
        
        ?>
        
        <form method ="post" name="UserCreateForm">
            
            <div class="return-ajax ds-none"></div>
            <input type="hidden" name="file" value="Users"/>
            <input type="hidden" name="action" value="Users_Update"/>
            <input type="hidden" name="user_id" value="<?= $id; ?>"/>
            
        <div style="width:90%; margin: 0 5%;" class="box bg-write padding20">    
            
            <label class="label">
                <span class="field">Nome:</span>
                <input
                    type="text"
                    name="user_name"
                    title="Informe seu primeiro nome"
                    placeholder="Informe seu primeiro nome"
                    value="<?= (isset($user_name) ? $user_name : ''); ?>"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">Sobrenome:</span>
                <input
                    type="text"
                    name="user_lastname"
                    title="Informe seu sobrenome"
                    value="<?= (isset($user_lastname) ? $user_lastname : ''); ?>"
                    placeholder="Informe seu sobrenome"
                    required
                    />
            </label>
            
            <label class="label">
                <span class="field">CPF:</span>
                <input
                    class="cpf"
                    type="text"
                    name="user_cpf"
                    title="Informe seu cpf"
                    value="<?= (isset($user_cpf) ? $user_cpf : ''); ?>"
                    placeholder="Informe seu cpf"
                    required
                    />
            </label>
            
            <label class="box box-large label">
                <span class="field">TELEFONE:</span>
                <input
                    class="telephone"
                    type="text"
                    name="user_telephone"
                    title="Informe seu telefone"
                    value="<?= (isset($user_telephone) ? $user_telephone : ''); ?>"
                    placeholder="Informe seu telefone"
                    required
                    />
            </label>
            
            <label class="box box-large label last">
                <span class="field">CELULAR:</span>
                <input
                    class="cell"
                    type="text"
                    name="user_cell"
                    title="Informe seu celular"
                    value="<?= (isset($user_cell) ? $user_cell : ''); ?>"
                    placeholder="Informe seu celular"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">E-mail:</span>
                <input
                    type="text"
                    name="user_email"
                    title="Informe seu melhor e-mail"
                    value="<?= (isset($user_email) ? $user_email : ''); ?>"
                    placeholder="Informe seu melhor email"
                    required
                    />
            </label>

            <div class="label_line bottom30">

                <label class="label_medium">
                    <span class="field">Senha (Pelo Menos 10 caracteres):</span>
                    <input
                        type="password"
                        name="user_password"
                        title="Informe sua senha de pelo menos 10 caracteres!"
                        placeholder="Informe sua senha de pelo menos 10 caracteres!"
                        />
                </label>
                
                <label class="box box-large last label_medium">
                    <span class="field">Gênero do Usuário:</span>
                    <select name="user_genre" title="Selecione o gênero do usuário" required >
                        <option value="">Selecione o Gênero do Usuário:</option>
                        <option <?php if($user_genre == 1): echo "selected='selected'"; endif; ?> value="1">Masculino</option> 
                        <option <?php if($user_genre == 0): echo "selected='selected'"; endif; ?> value="0">Feminino</option>
                    </select>
                </label>
            </div>    
        </div>
        <div style="margin-top: 20px; width: 90%; margin: 0 5%;" class="bg-write fl-right padding20 al-center">
            <button type="submit" title="Atualizar Usuário" class="btn btn-blue" value="Atualizar Usuário" name="SendPostForm">Atualizar Usuário</button>
            <img style="margin-left: 10px;" class="ajax_load" src="images/load.gif" title="Carregando..." alt="Carregando..."/>
        </div>      
    </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->