<?php

namespace _app\Conn;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * <b>Delete.class</b>
 * Classe responsável por deletar dados no banco de dados
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Delete extends Conn{
    
    private $Tabela;
    private $Termos;
    private $Places;
    private $Result;
    
    /** @var PDOStatement*/
    private $Delete;
    
    /** @var PDO */
    private $Conn;
    
    public function ExeDelete($Tabela, $Termos, $ParseString){
        $this->Tabela = (string) $Tabela;
        $this->Termos = (string) $Termos;
        parse_str($ParseString, $this->Places);
        
        $this->getSyntax();
        $this->Execute();
    }
    
    public function getResult(){
        return $this->Result;
    }
    
    public function getRowCount(){
        return $this->Delete->rowCount();
    }
    
    public function setPlaces($ParseString){
        parse_str($ParseString, $this->Places);
    }
    
    /**
     * ****************************************
     * ********** PRIVATE METHODS *************
     * ****************************************
     */
    
    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Delete = $this->Conn->prepare($this->Delete);
    }
    
    private function getSyntax(){
        $this->Delete = "DELETE FROM {$this->Tabela} {$this->Termos}";
    }
    
    private function Execute(){
        $this->Connect();
        try{
            $this->Delete->execute($this->Places);
            $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao Deletar:</b> {$e->getMessage()}", $e->getCode());
        }
    }
}
