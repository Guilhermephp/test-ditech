<?php
ob_start();
// PERMISSÃO PARA ACESSAR/INCLUIR OS ARQUIVOS DO FRAMEWORK, APENAS QUANDO ESTIVER DENTRO DESSE ARQUIVO(FRONT-CONTROLLER)
$permission_files = true;
require('./_app/Config.inc.php');
if(!file_exists('sitemap.xml.gz')):
    SiteMap(); 
    $gz = gzopen("sitemap.xml.gz", "w9");
    $sitemap = file_get_contents("sitemap.xml");
    gzwrite($gz, $sitemap);
    gzclose($gz);
    SitemapPing();
endif;
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
