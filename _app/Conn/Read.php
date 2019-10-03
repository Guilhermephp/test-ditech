<?php

namespace _app\Conn;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php
/**
 * <b>Read.class[ Conexão ]</b>
 * Classe responsável por leituras de dados no banco de dados
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Read extends Conn{
    
    private $Select;
    private $Places;
    private $Result;
    
    /** @var PDOStatement */
    private $Read;
    
    /** @var PDO */
    private $Conn;
    
    
    public function ExeRead($Tabela, $Termos = null, $ParseString = null){
        if(!empty($ParseString)){
            parse_str($ParseString, $this->Places);
        }
        $this->Select = "SELECT * FROM {$Tabela} {$Termos}";
        $this->Execute();
    }
    
    
    public function getResult(){
        return $this->Result;
    }
    
    public function getRowCount(){
        return $this->Read->rowCount();
    }
    
    public function getTabela(){
        return $this->Tabela;
    }
    
    public function getTermos(){
        return $this->Termos;
    }
    
    public function getPlaces(){
        return $this->Places;
    }
    
    public function FullRead($Query, $ParseString = null){
        $this->Select = (String) $Query;
        if(!empty($ParseString)){
            parse_str($ParseString, $this->Places);
        }
        $this->Execute();
    }
    
    public function SetPlaces($ParseString){
        parse_str($ParseString, $this->Places);
        $this->Execute();
    }
    
    /*
     * **************************************** 
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    
    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Read = $this->Conn->prepare($this->Select);
        $this->Read->setFetchMode(\PDO::FETCH_ASSOC); // modo de retorno, se retorna um array ou um objeto - retorna um array
    }

    private function getSyntax(){
       if($this->Places){
           foreach($this->Places as $Vinculo => $Valor){
               if($Vinculo == 'limit' || $Vinculo == 'offset'){
                   $Valor = (int) $Valor;
               }
               $this->Read->bindValue(":{$Vinculo}", $Valor, (is_int($Valor) ? \PDO::PARAM_INT : \PDO::PARAM_STR));
           }
       }
    }
    
    private function Execute(){
        $this->Connect();
        try{
            $this->getSyntax();
            $this->Read->execute(); 
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao Ler:</b> {$e->getMessage()}", $e->getCode());
        }
    }
}


