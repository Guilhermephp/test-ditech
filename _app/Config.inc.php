<?php
    if(!isset($permission_files) || empty($permission_files)):
        die();
    endif;
// AUTOLOAD DE CLASSES
    function autoload($Class){

        $prefix = "_app\\";
        $prefix_len = strlen($prefix);
         
        if(strncmp($Class, $prefix, $prefix_len) !== 0){ 
            trigger_error("Não existe essa classe {$Class}, pois falta o fornecedor Conn\\", E_USER_ERROR);
            die(); 
        }
       
        if(!strstr($Class, "Conn\\") && !strstr($Class, "Models\\") && !strstr($Class, "Helpers\\")){
            trigger_error("Não existe essa classe {$Class}, pois falta o namespace raiz Conn\\ ou Models\\ ou Helpers\\", E_USER_ERROR);
            die(); 
        }
        
        $idir = null;
        $relativeClass = substr($Class, $prefix_len);

        if(file_exists(__DIR__ . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $relativeClass) . ".php") && !is_dir(__DIR__ . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $relativeClass) . ".php")){
            $permission_class = true;
            include(__DIR__ . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $relativeClass) . ".php");
            $idir = true;
        }
        
        if(!$idir){
            trigger_error("Não existe essa classe {$Class}.class.php em seu sistema", E_USER_ERROR);
            die(); 
        }
    }
    
spl_autoload_register('autoload');

define('HOST','localhost'); // 198.136.59.241
define('USER','root'); // agencia4_nhonline
define('PASS','');
define('DBSA','system'); // agencia4_nhonline

define('HOME','http://localhost/test-ditech');
define('MAILUSER','kkk@agenciacion.com.br');
define('MAILPASS','k^&rLHN9V]b#');
define('MAILPORT','587');
define('MAILHOST','mail.agenciacion.com.br');    

define('THEME','system');
    
define('INCLUDE_PATH', HOME . '/themes/'. THEME); // inclusão DE css, imagens, mídias entre outros!
define('REQUIRE_PATH', 'themes' . DIRECTORY_SEPARATOR . THEME); // inclusão de arquivos e verificar a existência dos arquivos!  
    
// TRATAMENTO DE ERROS #####################
//CSS constantes :: Mensagens de Erro
define('WS_ACCEPT', 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');

//WSErro :: Exibe erros lanÃ§ados :: Front
function WSErro($ErrorMsg, $ErrorNo, $ErrorDie = null, $Class = null, $Style = null) {
    $CssClass = ($ErrorNo == E_USER_NOTICE ? WS_INFOR : ($ErrorNo == E_USER_WARNING ? WS_ALERT : ($ErrorNo == E_USER_ERROR ? WS_ERROR : $ErrorNo)));
    $Class = ( $Class ? " {$Class}" : '');
    $Style = ( $Style ? " style='{$Style}' " : ' ');
    return "<p{$Style}class=\"trigger radius {$CssClass}{$Class}\">{$ErrorMsg}<span class=\"ajax_close\"></span></p>";

    if ($ErrorDie):
        die;
    endif;
}

function ErrorFixed($message){
    return "<div class='form_mensagem Error-fixed pointer fixed al-center liberation liberation-22'><span class='close-window' title='Fechar Janela'>X</span><p class='liberation'>" . $message . "</p></div>";
}

//PHPErro :: personaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    echo "<p class=\"trigger {$CssClass}\">";
    echo "<b>Erro na Linha: #{$ErrLine} ::</b> {$ErrMsg}<br>";
    echo "<small>{$ErrFile}</small>";
    echo "<span class=\"ajax_close\"></span></p>";

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
?>  