<?php

/**
 * AdminUser.class[ MODEL ADMIN ]
 * Classe reponsável por gerenciar os usuários do sistema no admin!
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class AdminUser {
    
    private $UserId;
    private $Data;
    private $Result;
    private $Error;
    
    /**
     * <b>ExeCreate</b> - Esse método verifica se tem algum campo em branco, se tiver retorna um erro, 
     * caso contrário cadastra o usuário no banco!
     * @param ARRAY $Data = Recebe um array de dados do formulário para inserir os dados no banco!
     */
    public function ExeCreate(array $Data){
        $this->Data = $Data;
        $this->CheckData();
        if($this->CheckData()){
            if($this->CheckEmail()){
                $this->Create();
             }
         }    
     }
    
    /**
     * <b>ExeUpdate</b> - Esse método verifica se tem algum campo em branco, se tiver retorna um erro, 
     * caso contrário atualiza o usuário no banco!
     * @param INT $UserId = um int, que é o id do usuário, passado via get!
     * @param ARRAY $Data = um array com os dados vindos do formulário!
     */
    public function ExeUpdate($UserId, array $Data, string $Entity = "ws_users"){
        $this->UserId = $UserId;
        $this->Data = $Data;
        if($this->CheckData()){
            if($this->CheckEmail($Entity)){
                $this->Update($Entity);		
            }
        }
    }
    
    public function ExeDelete($UserId, string $Entity = "ws_users"){
        $this->UserId = (int) $UserId;
        
        $read = new \_app\Conn\Read;
        $read->ExeRead($Entity, 'WHERE user_id = :id', "id={$this->UserId}");
        if(!$read->getResult()){
            $this->Error = ["<b>ERRO AO DELETAR:</b> Esse usuário não existe no sistema, por favor utilize os botões!", WS_ALERT];
            $this->Result = false;
        }
       elseif(!empty($_SESSION['userlogin']) && $_SESSION['userlogin']['user_id'] == $this->UserId && $Entity === "ws_users"){
           $this->Error = ["<b>ERRO AO DELETAR:</b> Você não pode deletar o seu próprio usuário do sistema! O sistema precisa de pelo menos 1 <b>Administrador</b>!", WS_ALERT];
           $this->Result = false;
       }
        else{
            $this->Delete($Entity); // OBS: EM MANUTENÇÃO!
            $this->Result = $read->getResult()[0]['user_name'];
            /*if($read->getResult()[0]['user_level'] == 3){
                $userRead = $read;
                $userRead->ExeRead(self::Entity, "WHERE user_id != :id AND user_level = :lv", "id={$this->UserId}&lv=3");
                if(!$userRead->getResult()){
                    $this->Error = ["Erro ao deletar: Você não pode deletar o único administrador do sistema!", WS_ALERT];
                    $this->Result = false;
                }
                else{
                    $this->Delete();
                }
            }*/
        }
    }
    
    /**
     * <b>getResult</b> - Retorna o resultado para saber se realizou com sucesso a operação no sistema.
     * @return BOOLEAN = Para update e delete true ou false para saber se realizou com sucesso a operação no sistema!
     * @return BOOLEAN = Para insert retorna o id do ultimo dado inserido no banco! E para o select retorna um array 
     * com os dados para serem exibidos! usar um FOREACH para percorrer o array obtido!
     */
    public function getResult(){
        return $this->Result;
    }
    
    /**
     * <b>getError</b> - Retorna o erro que o sistema detectou pela operação atual!
     * @return ARRAY = Retorna um array com dois indices com o erro que o sistema detectou pela operação atual! 
     * Indice 0 é o erro e o indice 1 é o tipo de erro, se é um alert, infor, accept ou error.
     */
    public function getError(){
        return $this->Error;
    }
    
    
    /*
     * ****************************************
     * *********** PRIVATES METHODS ************
     * ****************************************
     */
    
    // Checa os dados vindos do formulário. Retira tags html e php. verifica o email e a senha.
    private function CheckData(){
        $pass = (!empty($this->Data['user_password'])? $this->Data['user_password'] : "");
        
        if(!empty($pass)):
            $this->Data['user_password'] = $pass;
        else:
            unset($this->Data['user_password']);
        endif;
        
        $this->Data = array_map('strip_tags', $this->Data);
        $this->Data = array_map('trim', $this->Data);
        $this->Data['user_level'] = 3;

        if(in_array('', $this->Data)){
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> Para criar um usuário, preencha todos os campos!", WS_ALERT];
            $this->Result = false;
        }
        elseif($this->Data['user_cpf'] !== "null" && !\_app\Helpers\Check::AlgorithmCpf($this->Data['user_cpf'])){
            $this->Result = false;
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> O CPF <b>{$this->Data['user_cpf']}</b> não é compatível com o sistema!", WS_ALERT];
        }
        elseif($this->Data['user_telephone'] !== "null" && !\_app\Helpers\Check::Phone($this->Data['user_telephone'])){
            $this->Result = false;
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> O telefone <b>{$this->Data['user_telephone']}</b> não é compatível com o sistema!", WS_ALERT];
        }
        elseif(!\_app\Helpers\Check::Cel($this->Data['user_cell'])){
            $this->Result = false;
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> O celular <b>{$this->Data['user_cell']}</b> não é compatível com o sistema!", WS_ALERT];
        }
        elseif(!\_app\Helpers\Check::Email($this->Data['user_email'])){
            $this->Result = false;
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> O email <b>{$this->Data['user_email']}</b> não é compatível com o sistema!", WS_ALERT];
        }
        elseif(!empty($pass) && strlen($pass) < 10){
            $this->Result = false;
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> A senha deve conter pelo menos 10 caracteres!", WS_ALERT];
        }
        else{
            if(isset($this->Data['user_cpf']) && !empty($this->Data['user_cpf']) && $this->Data['user_cpf'] === "null"){
                $this->Data['user_cpf'] = null;
            }
            if(isset($this->Data['user_cnpj']) && !empty($this->Data['user_cnpj']) && $this->Data['user_cnpj'] === "null"){
                $this->Data['user_cnpj'] = null;
            }
            if($this->Data['user_telephone'] === "null"){
                $this->Data['user_telephone'] = null;
            }
            if(isset($this->Data['user_complement']) && !empty($this->Data['user_complement']) && $this->Data['user_complement'] === "null"){
                $this->Data['user_complement'] = null;
            }
            return true;
        }
        
    }
    
    // Checa se o email o existe no banco! caso exista retorna um erro, se não retorna true!
    private function CheckEmail(string $Entity = "ws_users"){
        $Where = ($this->UserId ? "user_id != {$this->UserId} AND" : '');
        $readEmail = new \_app\Conn\Read;
        $readEmail->ExeRead($Entity, "WHERE {$Where} user_email = :email", "email={$this->Data['user_email']}");
        if($readEmail->getResult()){
            $this->Error = ["<b>ERRO AO ATUALIZAR:</b> O email <b>{$this->Data['user_email']}</b> já existe no sistema, digite outro!", WS_ALERT];
            $this->Result = false;
            return false;
        }
        else{
            return true;
        }
    }
    
    // Insere o usuário no banco de dados!
    private function Create(){
        if(isset($this->Data['user_password']) && !empty($this->Data['user_password'])){
            $this->Data['user_password'] = md5($this->Data['user_password']);
        } 
        $this->Data['user_registration'] = date('Y-m-d H:i:s');
        $insertUser = new \_app\Conn\Create;
        $insertUser->ExeCreate(self::Entity, $this->Data);
        if($insertUser->getResult()){
            $this->Result = $insertUser->getResult();
            $this->Error = ["O usuário <b>{$this->Data['user_name']} {$this->Data['user_lastname']}</b> foi cadastrado com sucesso no sistema! Para atualizar o mesmo <a href=\"painel.php?exe=users/update&userid={$this->getResult()}\">Clique Aqui</a>", WS_ACCEPT];
        }
    }
    
    // Atualiza o usuário do banco de dados!
    private function Update(string $Entity = "ws_users"){
        if(isset($this->Data['user_password']) && !empty($this->Data['user_password'])):
            $this->Data['user_password'] = md5($this->Data['user_password']);
        endif;
        $this->Data['user_lastupdate'] = date('Y-m-d H:i:s');
        $userUpdate = new \_app\Conn\Update;
        $userUpdate->ExeUpdate($Entity, $this->Data, 'WHERE user_id = :id', "id={$this->UserId}");
        if($userUpdate->getResult()){
            $this->Error = ["O usuário <b>{$this->Data['user_name']} {$this->Data['user_lastname']}</b> foi atualizado com sucesso no sistema!", WS_ACCEPT];
            $this->Result = true;
        }
    }
    
    // Delete o usuário do banco de dados!
    private function Delete(string $Entity = "ws_users"){
        $userDelete = new \_app\Conn\Delete;
        $userDelete->ExeDelete($Entity, 'WHERE user_id = :id', "id={$this->UserId}");
        if($userDelete->getResult()){
            $this->Error = ["O usuário <b>{$this->Result} {$this->Data['user_lastname']}</b> foi deletado com sucesso do sistema!", WS_ACCEPT];
        }
    }
    
}
