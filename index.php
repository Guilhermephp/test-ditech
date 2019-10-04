<?php
ob_start();
// PERMISSÃO PARA ACESSAR/INCLUIR OS ARQUIVOS DO FRAMEWORK, APENAS QUANDO ESTIVER DENTRO DESSE ARQUIVO(FRONT-CONTROLLER)
$permission_files = true;
require('./_app/Config.inc.php');
?>  
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
        <?php 
            header("Location: " . HOME . "/admin");
        ?>
    </body>
</html>
<?php ob_end_flush(); ?>
