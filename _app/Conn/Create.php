<?php

namespace _app\Conn;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * <b>Create.class[ Conexão ]</b>
 * Classe responsável por cadastrar dados no banco de dados
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Create extends Conn{
    
    private $Tabela;
    private $Dados;
    private $Result;
    
    /** @var PDOStatement */
    private $Create;
    
    /** @var PDO */
    private $Conn;
    
    /**
     * <b>ExeCreate:</b> Executa um cadastro no banco utilizando o prepared statements.
     * Basta informa o nome da tabela do banco e um array associativo ( nome da coluna => valor ).
     * @param STRING $Tabela = informe o nome da tabela do banco 
     * @param ARRAY $Dados = informe um array associativo ( nome da coluna => valor )
     */
    public function ExeCreate($Tabela, array $Dados){
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        
        $this->getSyntax();
        $this->Execute();
    }
    
    /**
     * <b>Obter resultado:</b> Retorna o ID do registro inserido ou FALSE caso nem um registro seja inserido! 
     * @return INT $Variavel = lastInsertId OR FALSE
     */
    public function getResult(){
        return $this->Result;
    }
    
    /*
     * **************************************** 
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    
    //Obtém o PDO e Prepara a query
    private function Connect(){
        $this->Conn = parent::getConn();
        $this->Create = $this->Conn->prepare($this->Create);
    }
    
    //Cria a sintaxe da query para Prepared Statements
    private function getSyntax(){
       $Fields = implode(', ', array_keys($this->Dados)); 
       $Places = ':' . implode(', :', array_keys($this->Dados));
       $this->Create = "INSERT INTO {$this->Tabela} ({$Fields}) VALUES ({$Places})";
    }
    
    //Obtém a Conexão e a Syntax, executa a query!
    private function Execute(){
        $this->Connect();
        try{
            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
        } catch (PDOException $e) {
            $this->Result = null;
            echo WSErro("<b>Erro ao cadastrar:</b> {$e->getMessage()}", $e->getCode());
        }
    }
}
