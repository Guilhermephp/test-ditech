<?php

namespace _app\Conn;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * Conn.class[ Conexão ]
 * Classe abstrata de conexão. Padrão Singleton
 * retorna um objeto PDO pelo método estático getConn();
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
abstract class Conn {
   
    private static $Host = HOST; 
    private static $User = USER;
    private static $Pass = PASS;
    private static $Dbsa = DBSA;
    
    
    /**
     * @var PDO
     */
    private static $Connect = null; // só vai executar a conexão, se o Connect estiver null, ou seja, não inicializado. 
                                    // numa próxima conexão, verifica se ele está null, se tiver cria um conexão, 
                                    // se não, usa a mesma a conexão com o banco, isso é o padrão singleton.
    
    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function Conectar(){
        
        try{
            if(self::$Connect == null){
                /**
                 * aqui se utiliza o dsn para banco de dados mysql
                 */
                $dsn = 'mysql:host=' . self::$Host . ';dbname='.self::$Dbsa; // dsn vai se conectar com o banco de dados mysql
                // PDO::MYSQL_ATTR_INIT_COMMAND é o indice de configuração que a gente quer acessar
                $options = [ \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8' ]; // configuração para o banco trabalhar com utf-8
                self::$Connect = new \PDO($dsn, self::$User, self::$Pass, $options); // options é em array
                
                /**
                 * o pdo permite que voce se conecte a varios tipos de banco de dados e manipule as queries da mesma maneira.
                 * o que muda é apenas o DSN do pdo. quando for utilizar outro banco de dados, é só pesquisar o dsn do banco
                 * no google. dsn para postgre.
                 */
            }            
        } 
        catch (PDOException $e){
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }
        
        self::$Connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }
    
    /**
     * 
     * Retorna um objeto PDO Singleton Pattern.*/
    protected static function getConn(){
        return self::Conectar();
    }
    
}
