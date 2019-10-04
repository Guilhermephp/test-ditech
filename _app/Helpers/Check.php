<?php

namespace _app\Helpers;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * Check.class[ HELPER ]
 * Classe responsável por manipular e validar dados no sistema
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Check {
   
    private static $Data;
    private static $Format;
    
    
    /**
     * <b>Verifica Largura e Altura de Uma Imagem:</b> Verifica a largura e altura de uma imagem definidos nos parâmetros do próprio método
     * @param STRING $File = $_FILES['indice_do_input_no_formulario']['tmp_name']
     * @return BOOL = True para uma imagem válida
     */
    public static function ImageGetSize($File, $ImageWidth, $ImageHeight){
        self::$Data = (string) $File;
        self::$Format = getimagesize(self::$Data);
        
        if(self::$Format[0] === $ImageWidth && self::$Format[1] === $ImageHeight):
            return true;
        else:
            return false;
        endif;
        
    }
    
    /**
     * <b>Verifica E-mail:</b> Executa validaÃ§Ã£o de formato de e-mail. Se for um email vÃ¡lido retorna true, ou retorna false.
     * @param STRING $Email = Uma conta de e-mail
     * @return BOOL = True para um email vÃ¡lido, ou false
    */
    public static function Email($Email){
        
        self::$Data = (string) $Email;
        self::$Format = '/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]{4}+(\.[a-z]{3}|\.[a-z]{3}\.[a-z]{2})$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function Cep($Cep){
        self::$Data = (string) $Cep;
        self::$Format = "/^[0-9]{5}+-+[0-9]{3}$/";

        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
    }

    public static function Numbers($Numbers){
        self::$Data = (string) $Numbers;
        self::$Format = "/^[0-9]{1,}$/";

        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * <b>Verifica Url:</b> Executa validaÃ§Ã£o de formato de url. Se for uma url com http ou https retorna true, ou retorna false.
     * @param STRING $Url = Uma url
     * @return BOOL = True para uma url vÃ¡lido, ou false
    */
    public static function Url($Url){
        
        self::$Data = (string) $Url;
        self::$Format = '/^(http|https):\/\/+www\.+[a-z]+\.[a-z]{3}+(||\.[a-z]{2})$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function UrlFacebook($Url){
        
        self::$Data = (string) $Url;
        self::$Format = '/^(https):\/\/+www\.+facebook+\.com+\/[a-z]{1,}$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function UrlInstagram($Url){
        
        self::$Data = (string) $Url;
        self::$Format = '/^(https):\/\/+www\.+instagram+\.com+\/[a-z]{1,}$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }
    
    public static function AlgorithmCpf($Cpf){
        
        if(strlen($Cpf) != 14){
            return false;
        }
        
        self::$Data = preg_replace('/[^0-9]/', '', $Cpf);
        
        $digitoA = 0;
        $digitoB = 0;

        for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
            $digitoA += self::$Data[$i] * $x;
        }

        for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
            if (str_repeat($i, 11) == self::$Data) {
                return false;
            }
            $digitoB += self::$Data[$i] * $x;
        }

        $somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11);
        $somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);

        if ($somaA != self::$Data[9] || $somaB != self::$Data[10]) {
            return false;
        } else {
            return true;
        }
    }
    
    public static function AlgorithmCnpj($Cnpj){
        
        if(strlen($Cnpj) != 18){
            return false;
        }
        
        $cnpj = preg_replace('/[^0-9]/', '', (string) $Cnpj);
	// Valida tamanho
	if(strlen($cnpj) != 14){
            return false;
        }    
        
        // Lista de CNPJs inválidos
        $invalidos = [
            '00000000000000',
            '11111111111111',
            '22222222222222',
            '33333333333333',
            '44444444444444',
            '55555555555555',
            '66666666666666',
            '77777777777777',
            '88888888888888',
            '99999999999999'
        ];

        // Verifica se o CNPJ está na lista de inválidos
        if(in_array($cnpj, $invalidos)){	
            return false;
        }
        
	// Valida primeiro dígito verificador
	for($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
	}
	$resto = $soma % 11;
	if($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)){
            return false;
        }        
	// Valida segundo dígito verificador
	for($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
	}
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    public static function Cpf($Cpf){   

        self::$Data = (string) $Cpf;
        self::$Format = '/^[0-9]{3}+.+[0-9]{3}+.+[0-9]{3}+-+[0-9]{2}$/'; 

        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }

    }

    public static function DateBirth($DateBirth){   

        self::$Data = (string) $DateBirth;  // 036.407.480-93
        self::$Format = '/^[0-9]{2}+\/+[0-9]{2}+\/+[0-9]{4}$/'; 

        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }

    }
    
    public static function DateDays90($dateTracking){
        
        $dateNow = date("d-m-Y"); // Data atual 

        if(isset($dateTracking) && !empty($dateTracking) && preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $dateTracking)){
            $dateTrackingReplace = str_replace("/", "-", $dateTracking); // DATA DO RASTREAMENTO
            if(isset($dateTrackingReplace) && !empty($dateTrackingReplace) && $dateTrackingReplace != "01-01-1970"){
                $dateTracking90Days = date("d-m-Y", strtotime("{$dateTrackingReplace}+90days"));  
                if(strtotime($dateNow) <= strtotime($dateTracking90Days)){
                    return true; //data é menor que os 90 dias
                }
                elseif(strtotime($dateNow) > strtotime($dateTracking90Days)){
                    return false; //data é maior que os 90 dias
                }
            }
            else{
                return false; // data incorreta
            }
        }
        else{
            return false;
        }   
    }
    
    public static function DateDays7($dateTracking){
        
        $dateNow = date("d-m-Y"); // Data atual 
        
        if(isset($dateTracking) && !empty($dateTracking) && preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $dateTracking)){
            $dateTrackingReplace = str_replace("/", "-", $dateTracking); // DATA DO RASTREAMENTO
            if(isset($dateTrackingReplace) && !empty($dateTrackingReplace) && $dateTrackingReplace != "01-01-1970"){
                $dateTracking7Days = date("d-m-Y", strtotime("{$dateTrackingReplace}+7days"));  
                if(strtotime($dateNow) <= strtotime($dateTracking7Days)){
                    return true; //data é menor que os 7 dias
                }
                elseif(strtotime($dateNow) > strtotime($dateTracking7Days)){
                    return false; //data é maior que os 7 dias
                }
            }
            else{
                return false; // data incorreta
            }
            return true;
        }
        else{
            return false;
        }
    }

    public static function Phone($Phone){
        
        self::$Data = (string) $Phone;
        self::$Format = '/^\(+[0-9]{2}+\)[0-9]{4}+-[0-9]{4}$/';
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function Cel($Cel){
        
        self::$Data = (string) $Cel;
        self::$Format = '/^\(+[0-9]{2}+\)9[0-9]{4}+-+[0-9]{4}$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        else{
            return false;
        }
        
    }

    public static function CelPhone($CelPhone){
        self::$Data = (string) $CelPhone;
        self::$Format = '/^\(+[0-9]{2}+\)[0-9]{4}+-[0-9]{4}$/';
        
        if(preg_match(self::$Format, self::$Data)){
            return true;
        }
        elseif(preg_match('/^\(+[0-9]{2}+\)9[0-9]{4}+-+[0-9]{4}$/', $CelPhone)){
            return true;
        }
        else{
            return false;
        }
    }
    
     /**
     * <b>Tranforma URL:</b> Tranforma uma string no formato de URL amigÃ¡vel e retorna o a string convertida!
     * @param STRING $Name = Uma string qualquer
     * @return STRING = $Data = Uma URL amigÃ¡vel vÃ¡lida
     */
    public static function Name($Name){
        self::$Format = array();
        self::$Format['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        self::$Format['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';
        
        self::$Data = strtr(utf8_decode($Name), utf8_decode(self::$Format['a']), self::$Format['b']);
        self::$Data = strip_tags(trim(self::$Data));
        self::$Data = str_replace(' ', '-', self::$Data);
        self::$Data = str_replace(array('-----', '----', '---', '--'), '-', self::$Data);
          
        return strtolower(utf8_encode(self::$Data));
    }
    
    /**
     * <b>Tranforma Data:</b> Transforma uma data no formato DD/MM/YY em uma data no formato TIMESTAMP!
     * @param STRING $Name = Data em (d/m/Y) ou (d/m/Y H:i:s)
     * @return STRING = $Data = Data no formato timestamp!
     */
    public static function Data($Data){
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);
        
        if(empty(self::$Format[1])){
            self::$Format[1] = date('H:i:s'); // ,(strtotime('-4hours'))
        }
        
        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
        return self::$Data;
    }

    public static function DateFormat($Data){
        self::$Format = $Data;
        self::$Data = explode('/', self::$Format);
        
        self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0];
        return self::$Data;
    }
    
    public static function is_day($Day){
        if(!is_numeric($Day)){
            return false;
        }
        elseif($Day < 0){
            return false;
        }
        elseif(strlen($Day) > 2){
            return false;
        }
        elseif($Day > 31){
            return false;
        }
        else{
            return true;
        }
    }
    
    public static function is_month($Month){
        if(!is_numeric($Month)){
            return false;
        }
        elseif($Month < 0){
            return false;
        }
        elseif(strlen($Month) > 2){
            return false;
        }
        elseif($Month > 12){
            return false;
        }
        else{
            return true;
        }
    }

    public static function is_year($Month){
        if(!is_numeric($Month)){
            return false;
        }
        elseif($Month < 0){
            return false;
        }
        else{
            return true;
        }
    }
    
    public static function DataMonthToPt($Month){
        self::$Format = ["Jan" => "Janeiro", "Feb" => "Fevereiro", "Mar" => "Março", "Apr" => "abril", "May" => "Maio", "Jun" => "Junho", "Jul" => "Julho", "Aug" => "Agosto", "Sep" => "Setembro", "Oct" => "Outubro", "Nov" => "Novembro", "Dec" => "Dezembro"];
        if(key_exists($Month, self::$Format)){
            self::$Data = self::$Format[$Month];
            return self::$Data;
        }
        else{
            return false;
        }
    }
    
    public static function DataExpirationProduct($Data){
        self::$Format = explode(' ', $Data);
        self::$Data = explode('/', self::$Format[0]);
        
        if(empty(self::$Format)):
            return false;
        elseif(in_array("", self::$Data) || empty(self::$Data)):
            return false;
        elseif(!isset(self::$Format[1]) || empty(self::$Format)):
            return false;
        else:
            $time = explode(":", self::$Format[1]);
            if(count(self::$Data) < 3 || in_array("", self::$Data)):
                return false;
            elseif(self::$Data[0] > 31):
                return false;
            elseif(self::$Data[1] > 12):
                return false;
            elseif(count($time) < 3 || in_array("", $time)):
                return false;
            elseif($time[0] > 24):
                return false;
            elseif($time[1] > 59):
                return false;
            elseif($time[2] > 59):
                return false;
            else:
                self::$Data = self::$Data[2] . '-' . self::$Data[1] . '-' . self::$Data[0] . ' ' . self::$Format[1];
            endif;
            return self::$Data;
        endif;
    }
    
    /**
     * <b>Limita as Palavras:</b> Limita a quantidade de palavras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @return INT = $Limite = String limitada pelo $Limite
     */
    public static function Words($String, $Limite, $Pointer = null){
        self::$Data = strip_tags(trim($String));
        self::$Format = (int) $Limite;
        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));
        
        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer);
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data);
        return $Result;
    }
    
    public static function WordsNotags($String, $Limite, $Pointer = null){
        self::$Data = $String;
        self::$Format = (int) $Limite;
        $ArrWords = explode(' ', self::$Data);
        $NumWords = count($ArrWords);
        $NewWords = implode(' ', array_slice($ArrWords, 0, self::$Format));
        
        $Pointer = (empty($Pointer) ? '...' : ' ' . $Pointer);
        $Result = ( self::$Format < $NumWords ? $NewWords . $Pointer : self::$Data);
        return $Result;
    }
    
    /**
     * <b>Limita os Letras:</b> Limita a quantidade de letras a serem exibidas em uma string!
     * @param STRING $String = Uma string qualquer
     * @return INT = $Limite = String limitada pelo $Limite
     */
    public static function Chars($String, $Limite) {
        self::$Data = strip_tags($String);
        self::$Format = $Limite;
        if (strlen(self::$Data) <= self::$Format) {
            return self::$Data;
        } 
        else {    
            $subStr = strrpos(substr(self::$Data, 0, self::$Format), ' ');
            return substr(self::$Data, 0, $subStr) . '...';
        }
    }
    
    /**
     * <b>Obter categoria:</b> Informe o name (url) de uma categoria para obter o ID da mesma.
     * @param STRING $category_name = URL da categoria
     * @return INT $category_id = id da categoria informada
     */
    public static function CatByName($CategoryName){
        $read = new \_app\Conn\Read;
        $read->ExeRead("ws_categories", "WHERE category_name = :name", "name=$CategoryName");
        
        if($read->getRowCount()){
            return $read->getResult()[0]['category_id'];
        }
        else{
            echo "A categoria {$CategoryName} não foi encontrada";
            die;
        }
    }
    
    /**
     * <b>UsuÃ¡rios Online:</b> Ao executar este HELPER, ele automaticamente deleta os usuÃ¡rios expirados. Logo depois
     * executa um READ para obter quantos usuÃ¡rios estÃ£o realmente online no momento!
     * @return INT = Qtd de usuÃ¡rios online
     */
    public static function UserOnline(){
        $now = date('Y-m-d H:i:s');
        $deleteUserOnline = new \_app\Conn\Delete;
        $deleteUserOnline->ExeDelete('ws_siteviews_online', "WHERE online_endview < :now", "now=$now");
        
        $readUserOnline = new \_app\Conn\Read;
        $readUserOnline->ExeRead('ws_siteviews_online');
        return $readUserOnline->getRowCount();
    }
    
    /**
     * <b>Imagem Upload:</b> Ao executar este HELPER, ele automaticamente verifica a existencia da imagem na pasta
     * uploads. Se existir retorna a imagem redimensionada!
     * @return RETORNA COM A TAG HTML IMG, COM A IMAGEM REDIMENSIONADA!
     * @param STRING $ImageUrl = informe a pasta e o nome da imagem vindo do banco de dados! ex: '../uploads/' . $post_cover
     */
    public static function Image($ImageUrl, $ImageDir, $ImageDesc, $ImageW = null, $ImageH = null, $clas = null) {

        self::$Data = $ImageUrl;

        if (file_exists($ImageDir) && !is_dir($ImageDir)):
            $patch = HOME;
            $imagem = self::$Data;
            $class = ( $clas ? "class=\"{$clas}\"" : '');
            return "<img $class src=\"{$patch}/tim.php?src={$patch}/{$imagem}&w={$ImageW}&h={$ImageH}\" alt=\"[{$ImageDesc}!]\" title=\"{$ImageDesc}\"/>";
        else:
            return false;
        endif;
    }
    
    public static function UserId($UserId){
        if(!is_numeric($UserId)){
            return false;
        }
        elseif($UserId <= 0){
            return false;
        }
        else{
            $read_user_id = new \_app\Conn\Read;
            $read_user_id->FullRead("SELECT user_id FROM users WHERE user_id = :user_id", "user_id={$UserId}");
            if($read_user_id->getResult()){
                return true;
            }
            else{
                return false;
            }
        } 
    }
    
    public static function UserProducts($UserProducts){
        if(!is_array($UserProducts)){
            return false;
        }
        else{
            $read_products_id = new \_app\Conn\Read;
            foreach($UserProducts as $products){
                $verification = true;
                if(empty($products)){
                    $verification = false;
                    break;
                }
                elseif(!is_numeric($products)){
                    $verification = false;
                    break;
                }
                elseif($products <= 0){
                    $verification = false;
                    break;
                }
                else{
                    $read_products_id->FullRead("SELECT product_id FROM products WHERE product_id = :product_id", "product_id={$products}");
                    if(!$read_products_id->getResult()){
                        $verification = false;
                        break;
                    }
                } 
            }
            if(isset($verification) && !empty($verification)){
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    public static function UserQuantitys($UserQuantitys){
        if(!is_array($UserQuantitys)){
            return false;
        }
        else{
            foreach($UserQuantitys as $quantitys){
                $verification = true;
                if(empty($quantitys)){
                   $verification = false;
                    break; 
                }
                elseif(!is_numeric($quantitys)){
                    $verification = false;
                    break;
                }
                elseif($quantitys <= 0){
                    $verification = false;
                    break;
                } 
            }
            if(isset($verification) && !empty($verification)){
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    public static function barCodeProduct(array $arrayBarCode, $idProduct, array $arraySizes){
        $readId = new Read;
        $readBarCode = new Read;
        $readProductSize = new Read;
        $a = -1;
        if(!empty($arrayBarCode)):
            foreach($arrayBarCode as $barCodes):
                $a++;
                $readId->FullRead("SELECT id FROM products_sizes_multiple_ecommerce WHERE product_id_multiple = :product_id_multiple AND size_id_multiple = :size_id_multiple", "product_id_multiple={$idProduct}&size_id_multiple={$arraySizes[$a]}");
                if($readId->getResult()):
                    $readBarCode->FullRead("SELECT id FROM products_sizes_multiple_ecommerce WHERE id != :id AND bar_code_ecommerce = :bar_code_ecommerce", "id={$readId->getResult()[0]['id']}&bar_code_ecommerce={$barCodes}");
                    if($readBarCode->getResult()):
                        return false;
                    else:
                        return true;
                    endif;
                endif;
                $readProductSize->FullRead("SELECT product_id_multiple, size_id_multiple FROM products_sizes_multiple_ecommerce WHERE bar_code_ecommerce = :bar_code_ecommerce", "bar_code_ecommerce={$barCodes}");
                if($readProductSize->getResult()):
                    if($readProductSize->getResult()[0]['product_id_multiple'] != $idProduct || $readProductSize->getResult()[0]['size_id_multiple'] != $arraySizes[$a]):
                        return false;
                    else:
                        return true;
                    endif;
                endif;
            endforeach;
        endif;
    }    
    
    public static function barCodeChars(array $arrayBarCodeChars){
        if(!empty($arrayBarCodeChars)):
            foreach($arrayBarCodeChars as $barCodesChars): 
                if(strlen($barCodesChars) > 25):
                    return false;
                else:
                    return true;    
                endif;
            endforeach;
        endif;
    }  
    
    public static function CountQuantityStock(array $arrayQuantityStock){
        if(!empty($arrayQuantityStock)):
            foreach($arrayQuantityStock as $quantityStock):
               if(!Check::Numbers($quantityStock)):
                   return false;
               else:
                   return true;
               endif; 
            endforeach;
        endif;
    }
    
    public static function NumbersProducts($values){
        if(preg_match("/^[0-9]{1,}.[0-9]{1,},[0-9]{1,}$/", $values)):
            return true;
        elseif(preg_match("/^[0-9]{1,},[0-9]{1,}$/", $values)):
          return true;
        elseif(preg_match("/^[0-9]{1,}$/", $values)):
            return true;
        else:
            return false;    
        endif;
    }
    
}