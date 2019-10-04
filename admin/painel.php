<?php
ob_start();
$permission_files = true;
require('../_app/Config.inc.php');
session_start();
$login = new _app\Models\Login(3);
$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
$getexe = filter_input(INPUT_GET, 'cc', FILTER_DEFAULT);

if(!$login->CheckLogin()){
    unset($_SESSION['userlogin']);
    header('location:index.php?cc=restrito');
    exit();
}
else{
    $userLogin = $_SESSION['userlogin'];
}

if($logoff){
    unset($_SESSION['userlogin']);
    header('location:index.php?cc=logoff');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="robots" content="noindex, nofollow"/> 
        <title>Bem Vindo(a) ao Coffee Control - Coffee Control</title>
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700,800'/>
        <link rel="stylesheet" href="<?= HOME; ?>/admin/css/admin.css"/>
        <link rel="stylesheet" href="<?= HOME; ?>/admin/css/media-boot.css"/>
        <script src="<?= HOME; ?>/_cdn/js/jquery.js"></script>
        <script src="<?= HOME; ?>/_cdn/js/jquery.form.min.js"></script>
        <script src="<?= HOME; ?>/_cdn/js/jquery.mask.js"></script>
        <script async src="<?= HOME; ?>/admin/_js/coffeecontrol.js"></script>
    </head>
    <body>
        <header class="painel-header">
            <div class="content">
                <div class="fl-right header-logout">
                    <span class="fl-left time fontsizeb font400 bottom10">Olá <b style="color:blue;"><?= strtoupper($userLogin['user_name']); ?></b>, Hoje <?= date("d/m/Y H:i:s"); ?></span>
                    <a class="sair fl-left btn btn-red radius font400 fontsizeb" href="painel.php?logoff=true" title="Sair do Painel">Sair do Painel!</a>
                </div>
                <div title="ABRIR / FECHAR MENU" class="men">
                    <div class="menu1"></div>
                </div>
                <div class="clear"></div>
            </div>
        </header>

        <nav class="navadmin">
                    <h1 class="fontzero">Dashboard</h1>
                    <?php
                        //ATIVA MENU
                        if(isset($getexe)):
                            $linkto = explode('/', $getexe);
                        else:
                            $linkto = array();
                        endif;
                    ?>
                    <div class="logomarca ds-none"><img src="images/novo.png" class="logo"/></div>
                    <ul class="menu">
                        <li class="li<?php if(in_array('home', $linkto)): echo ' active'; endif; ?>"><a title="Dashboard" class="opensub" href="painel.php?cc=home">Dashboard</a></li>
                        <li class="li<?php if(in_array('rooms', $linkto)): echo ' active'; endif; ?>"><a title="Usuários" class="opensub" href="painel.php?cc=rooms/index">Salas</a>
                            <ul class="sub">
                                <li>
                                    <a title="Cadastrar Sala" href="painel.php?cc=rooms/create">
                                        Cadastrar Sala
                                    </a>
                                </li>
                                <li>
                                    <a title="Listar Salas" href="painel.php?cc=rooms/index">
                                        Listar Salas
                                    </a>
                                </li>
                                <li>
                                    <a title="Reservar Sala" href="painel.php?cc=rooms/reserve">
                                        Reservar Sala
                                    </a>
                                </li>
                            </ul>
                        </li>  
                        <li class="li<?php if(in_array('users', $linkto)): echo ' active'; endif; ?>"><a title="Usuários" class="opensub" href="painel.php?cc=users/users">Usuários</a>
                            <ul class="sub">
                                <li><a title="Cadastrar Usuário" href="painel.php?cc=users/create">Cadastrar Usuário</a></li>
                                <li><a title="Listar Usuários" href="painel.php?cc=users/users">Listar Usuários</a></li>
                            </ul>
                        </li>  
                    </ul>
        </nav>      
            
        <div id="painel">
            <?php
                // que vai ser passado dentro da variavel $getexe, que tem um filtro que recebe os dados via get pelo exe.
                // exe=posts/create  posts é a pasta e create é o arquivo, primeiro indice é a pasta e o segundo é o arquivo.
                if(isset($_SESSION['userlogin']) && !empty($_SESSION['userlogin'])):
                    if(!empty($getexe)):
                        $includepatch = __DIR__ . DIRECTORY_SEPARATOR .'system' . DIRECTORY_SEPARATOR . strip_tags(trim($getexe)) . '.php';
                        if (file_exists($includepatch)):
                            require_once($includepatch);
                        else:
                            header("Location:painel.php?cc=home");
                        endif;
                    else:
                        header("Location:painel.php?cc=home");
                    endif;
                else:
                    die();
                endif;
            ?>   
        </div>
    </body>
</html>
<?php ob_end_flush(); ?>