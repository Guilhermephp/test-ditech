<?php

namespace _app\Models;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;

/*
 * Classe responsável para fazer a integração com o WebService de rastreamento dos correios, através do método SOAP, para 
 * buscar as informações do objetos postados!
 */

/**
 * @author Guilherme Natus
 */
class TrackingObjects {
    
    private static $User = USER_TRACKING;
    private static $Pass = PASS_TRACKING;
    private static $Url = URL_TRACKING;
    private static $Type = "L";
    private static $Result = "T";
    private static $Language = "101";
    
    // Atributo que instância a classe SOAP e conecta com o WebService de rastreamento dos correios 
    private $Soap;
    
    // Atributo que recebe o array com os parametros e enviar-los ao WebService
    private $Data = array();
    
    // Atributo que retorna o status do objeto para ver se o pedido já foi entregue/se retornou a agência dos correios
    private $ResultSoap;
    
    public function __construct(){
        $this->Soap = new SoapClient(self::$Url);
    }
    
    public function getObject(string $Object){
        
        $this->Data = [
            'usuario' => self::$User,
            'senha' => self::$Pass,
            'tipo' => self::$Type,
            'resultado' => self::$Result,
            'lingua' => self::$Language,
            'objetos' => $Object
        ];
        
        $this->ResultSoap = $this->Soap->buscaEventos($this->Data);  
    }
    
    public function getResult(){
        if(isset($this->ResultSoap->return->objeto->evento->status) && !empty($this->ResultSoap->return->objeto->evento->status)):
            return $this->ResultSoap->return->objeto->evento->status;
        endif;
    }
    
    public function getResultDate(){
        if(isset($this->ResultSoap->return->objeto->evento->data) && !empty($this->ResultSoap->return->objeto->evento->data)):
            return $this->ResultSoap->return->objeto->evento->data;
        endif;
    }
}
