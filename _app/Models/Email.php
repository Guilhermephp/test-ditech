<?php

namespace _app\Models;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

require_once(__DIR__ . '/../Library/PHPMailer/class.phpmailer.php');
require_once(__DIR__ . '/../Library/PHPMailer/class.smtp.php');

/**
 * Email.class[ MODEL ]
 * Modelo responsável por configurar a PHPMailer, validar os dados e disparar emailsdo sistema!
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Email {
    
    /** @var PHPMailer */
    private $Mail;
    
    /** EMAIL DATA */
    private $Data;
    
    /** CORPO DO EMAIL */
    private $Assunto;
    private $Mensagem;
    
    /** REMETENTE */
    private $RemetenteNome;
    private $RemetenteEmail;
    
    /** DESTINO */
    private $DestinoNome;
    private $DestinoEmail;
    
    /** CONTROLE */
    private $Error;
    private $Result;
    
    function __construct(){
        $this->Mail = new \PHPMailer;
        $this->Mail->Host = MAILHOST;
        $this->Mail->Port = MAILPORT;
        $this->Mail->Username = MAILUSER;
        $this->Mail->Password = MAILPASS;
        $this->Mail->CharSet = "UTF-8";
    }
    
    // Método para enviar um email e retira as tags html
    public function Enviar(array $Data){
        $this->Data = $Data;
        $this->Clear();
        
        if(in_array("", $this->Data)){
            $this->Error = ["Erro ao enviar mensagem: Para enviar esse email, preencha todos os campos!", WS_ALERT];
            $this->Result = false;
        }
        elseif(!Check::Email($this->Data['RemetenteEmail'])){
            $this->Error = ["Erro ao enviar mensagem: O email informado é inválido!", WS_ALERT];
            $this->Result = false;
        }
        else{
            $this->setMail();
            $this->Config();
            $this->SendMail();
        }
    }


    // Método para enviar um email montando a mensagem no código e não retira as tags html
    public function EnviarMontando(array $Data){
        $this->Data = $Data;
        
        if(in_array("", $this->Data)){
            $this->Error = ["Erro ao enviar mensagem: Para enviar esse email, preencha todos os campos!", WS_ALERT];
            $this->Result = false;
        }
        elseif(!\_app\Helpers\Check::Email($this->Data['RemetenteEmail'])){
            $this->Error = ["Erro ao enviar mensagem: O email informado é inválido!", WS_ALERT];
            $this->Result = false;
        }
        else{
            $this->setMail();
            $this->Config();
            $this->SendMail();
        }
    }
    
    public function getResult(){
        return $this->Result;
    }
    
    public function getError(){
        return $this->Error;
    }
    
    // PRIVATES
    
    private function Clear(){
        $this->Data = array_map("strip_tags", $this->Data);
        $this->Data = array_map("trim", $this->Data);
    }
    
    private function setMail(){
        $this->Assunto = $this->Data["Assunto"];
        $this->Mensagem = $this->Data["Mensagem"];
        $this->RemetenteNome = $this->Data["RemetenteNome"];
        $this->RemetenteEmail = $this->Data["RemetenteEmail"];
        $this->DestinoNome = $this->Data["DestinoNome"];
        $this->DestinoEmail = $this->Data["DestinoEmail"];
        
        $this->Data = null;
        $this->setMsg();
    }
    
    private function setMsg(){
        // isso é importante porque a informação da data no corpo do email pode evitar o span!
        $this->Mensagem = $this->Mensagem;
    }
    
    private function Config(){
        //SMTP AUTH - autenticação do smtp!
        $this->Mail->IsSMTP(); // dizendo que vai ser um email em smtp!
        $this->Mail->SMTPAuth = true; // dizendo que vai ser uma email autenticado em smtp!
        $this->Mail->IsHTML(); // dizendo que vai ser um email em html!
        // $this->Mail->SMTPSecure = 'tls';
        
        //REMETENTE E RETORNO - dados do remetente e pra quem a gente vai responder!
        $this->Mail->From = MAILUSER; // quem vai mandar o email, sempre o próprio email vai disparar!
        //$this->Mail->FromName = $this->DestinoNome; nome de quem vai mandar o email!
        //$this->Mail->AddReplyTo($this->DestinoEmail, $this->DestinoNome); para quem vai enviar a resposta(sempre mandar para o seu email smtp)
        $this->Mail->FromName = $this->DestinoNome; //nome de quem vai mandar o email!
        $this->Mail->AddReplyTo($this->RemetenteEmail, $this->RemetenteNome); // para quem vai enviar a resposta(sempre mandar para o seu email smtp)
        
        //ASSUNTO, MENSAGEM E DESTINO
        $this->Mail->Subject = $this->Assunto; // assunto!
        $this->Mail->Body = $this->Mensagem; // corpo!
        //$this->Mail->AddAddress($this->RemetenteEmail, $this->RemetenteNome); endereço que recebe o email!
        $this->Mail->AddAddress($this->DestinoEmail, $this->DestinoNome); // endereço que recebe o email!
        // o destino não implica no remetente, nem em quem está enviando(MAILUSER), apenas quem está recebendo.
        // É INTERESSANTE QUE O MESMO DOMÍNIO QUE ENVIE, SEMPRE SEJA O MESMO QUE RECEBE PORQUE EVITA MUITO SPAN!
        // cursos@upinside.com.br envia o email para suporte@upinside.com.br
        // É INTERESSANTE QUE VOCÊ TENHAS 2 EMAILS: UM SÓ PARA O SISTEMA, QUE É SÓ PARA ENVIAR E QUANDO 
        // RESPONDEREM NESSE, VOCÊ TER UMA AUTO-RESPOSTA PARA FALAR QUE ESSE EMAIL NÃO É MONITORADO E
        // QUE É PARA MANDAR PARA O OUTRO, QUE É O QUE SEMPRE É MONITORADO E TER ESSE OUTRO SÓ PARA RECEBER OS EMAILS!
    }
    
    private function SendMail(){
        if($this->Mail->Send()){ 
            $this->Error = ["Obrigado por entrar em contato: Recebemos sua mensagem e estaremos respondendo em breve!", WS_ACCEPT];
            $this->Result = true;
        }
        else{
            $this->Error = ["Erro ao enviar: Entre em contato com o admin. ( {$this->Mail->ErrorInfo} )", WS_ERROR];
            $this->Result = false;
        }
    }
    
}
