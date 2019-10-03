<?php

namespace _app\Models;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * Link.class[ MODEL ]
 * Classe responsável por organizar o SEO do sistema e realizar a navegação!
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Link {
    
    private $File; //arquivo, index, categoria, artigo!
    private $Link; //nome para buscar no banco de dados ex: nome de um artigo, categoria. ex: artigo/php-a-linguagem-de-programacao!
    
    /** DATA */
    private $Local; //url completa que está acessando!
    private $Path; //caminho e o arquivo de inclusão para fazer a navegação!
    private $Tags; //obter tags!
    private $Data; //obter dados do banco!
    
    /** @var Seo */
    private $Seo; //objeto da classe Seo!
    
    function __construct(){
        $this->Local = strip_tags(filter_input(INPUT_GET,'url',FILTER_DEFAULT));
        $this->Local = ($this->Local ? $this->Local : 'index');
        $this->Local = explode('/', $this->Local);
        $this->Local = array_map('trim', $this->Local);
        $this->File = (!empty($this->Local[0]) ? $this->Local[0] : 'index');
        $this->Link = (!empty($this->Local[1]) ? $this->Local[1] : null);
        $this->Seo = new Seo($this->File, $this->Link, (isset($this->Local[2]) && !empty($this->Local[2]) ? $this->Local[2] : ""));
    }
    
    public function getTags(){
        $this->Tags = $this->Seo->getTags();
        return $this->Tags;
    }
    
    public function getData(){
        $this->Data = $this->Seo->getData();
        return $this->Data;
    }
    
    public function getLocal(){
        return $this->Local;
    }
    
    public function getPath(){
        $this->setPath();
        return $this->Path;
    }
    
    //private
    
    private function setPath(){
        if(file_exists('./' . REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . '.php')){
            $this->Path = REQUIRE_PATH . DIRECTORY_SEPARATOR . $this->File . '.php';
        }
        else{
            $this->Path = './' . REQUIRE_PATH . DIRECTORY_SEPARATOR . '404.php';
        }
    }
    
}
