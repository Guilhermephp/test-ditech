<?php

namespace _app\Helpers;

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * Session.class[ HELPER ]
 * Classe responsável pelas estatísticas, sessões e atualizações de trafego do sistema.
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Session {
    
    private $Date;
    private $Cache;
    private $Traffic;
    private $Browser;
    
    //pega os usuários online de acordo com o cache
    public function getUsersOnline(){
        $readUserOnline = new \_app\Conn\Read;
        $readUserOnline->ExeRead('ws_siteviews_online', 'WHERE online_session = :onses', "onses={$_SESSION['useronline']['online_session']}");
        return $readUserOnline->getRowCount();
    }
    
    //deleta os usuários expirados do sistema
    private function SessionClear(){
        $Now = date('Y-m-d H:i:s');
        $deleteUser = new \_app\Conn\Delete;
        $deleteUser->ExeDelete('ws_siteviews_online', "WHERE online_endview < :now", "now={$Now}");
    }

    //verifica e executa todos os métodos da classe
    function __construct($Cache = null){
        $this->SessionClear();
        $this->Date = date('Y-m-d');
        $this->Cache = ( (int) $Cache ? $Cache : 20 );
    }
    
    public function CheckSession(){
        $url = FILTER_INPUT(INPUT_SERVER, "REQUEST_URI", FILTER_DEFAULT);
        if(!strpos($url, ".")):
            session_start();
            if(!isset($_SESSION['useronline'])):
            $this->setSession();
            $this->CheckBrowser();
            $this->setTraffic();
            $this->setUsuario();
            $this->BrowserUpdate();
            else:
            $this->sessionUpdate();
            $this->CheckBrowser();
            $this->TrafficUpdate();
            $this->UsuarioUpdate();
            endif;
        endif;    
            
        $this->Date = null;
    }
    
    /*
     * ****************************************
     * ********** SESSÃO DO USUÁRIO ***********
     * ****************************************
     */
    
    //inicia a sessão do usuário 
    private function setSession(){
        $_SESSION['useronline'] = [
            "online_session" => session_id(),
            "online_startview" => date('Y-m-d H:i:s'),
            "online_endview" => date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes")),
            "online_ip" => FILTER_INPUT(INPUT_SERVER, "REMOTE_ADDR", FILTER_VALIDATE_IP),
            "online_url" => FILTER_INPUT(INPUT_SERVER, "REQUEST_URI", FILTER_DEFAULT),      
            "online_agent" => FILTER_INPUT(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT)   
        ];
    }
    
    //atualiza a sessão do usuário
    private function sessionUpdate(){
        $_SESSION['useronline']['online_endview'] = date('Y-m-d H:i:s', strtotime("+{$this->Cache}minutes"));
        $_SESSION['useronline']['online_url'] = FILTER_INPUT(INPUT_SERVER, "REQUEST_URI", FILTER_DEFAULT);
    }
    
    /*
     * ****************************************
     * *** USUÁRIOS, VISITAS E ATUALIZAÇÕES ***
     * ****************************************
     */

    //verifica e insere o tráfego na tabela
    private function setTraffic(){
        if(isset($this->Browser) && $this->Browser){
            $this->getTraffic();
            if(!$this->Traffic){
                $ArrSiteViews = [ 'siteviews_date' => $this->Date, 'siteviews_users' => 1, 'siteviews_views' => 1, 'siteviews_pages' => 1];
                $createSiteViews = new \_app\Conn\Create;
                $createSiteViews->ExeCreate('ws_siteviews', $ArrSiteViews); 
            }
            else{
                if(!$this->getCookie()){
                    $ArrSiteViews = [ 'siteviews_users' => $this->Traffic['siteviews_users'] + 1, 'siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
                }
                else{ 
                    $ArrSiteViews = [ 'siteviews_views' => $this->Traffic['siteviews_views'] + 1, 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
                }
            }
            $updateSiteViews = new \_app\Conn\Update;
            $updateSiteViews->ExeUpdate('ws_siteviews', $ArrSiteViews, 'WHERE siteviews_date = :date', "date={$this->Date}");      
        }
    }
    
    //verifica e atualiza os pageviews
    private function TrafficUpdate(){
        if(isset($this->Browser) && $this->Browser){
            $this->getTraffic();
            if(!$this->Traffic){
                $ArrSiteViews = [ 'siteviews_date' => $this->Date, 'siteviews_users' => 1, 'siteviews_views' => 1, 'siteviews_pages' => 1];
                $createSiteViews = new \_app\Conn\Create;
                $createSiteViews->ExeCreate('ws_siteviews', $ArrSiteViews); 
            }
            else{
                $ArrSiteViews = [ 'siteviews_pages' => $this->Traffic['siteviews_pages'] + 1];
                $updatePageViews = new \_app\Conn\Update;
                $updatePageViews->ExeUpdate('ws_siteviews', $ArrSiteViews, 'WHERE siteviews_date = :date', "date={$this->Date}");
            }
        }
        $this->Traffic = null;
    }

    //obtem dados da tabela [ HELPER TRAFFIC ]
    //ws_siteviews
    private function getTraffic(){
        $readSiteViews = new \_app\Conn\Read;
        $readSiteViews->ExeRead('ws_siteviews', 'WHERE siteviews_date = :date', "date={$this->Date}");
        if($readSiteViews->getRowCount()){
            $this->Traffic = $readSiteViews->getResult()[0];
        }
    }
    
    //verifica, cria e atualiza o cookie o usuário [ HELPER TRAFFIC ]
    private function getCookie(){
        $Cookie = filter_input(INPUT_COOKIE, 'useronline', FILTER_DEFAULT);
        setcookie("useronline", base64_encode("upinside"), time() + 86400);
        if(!$Cookie){
            return false;
        }
        else{ 
            return true;
        }
    }
    
    /*
     * ****************************************
     * ******** NAVEGADORES DE ACESSO *********
     * ****************************************
     */
    
    //identifica navegador do usuário
    private function CheckBrowser(){
        $this->Browser = $_SESSION['useronline']['online_agent'];
        if(strpos($this->Browser, "Googlebot") || strpos($this->Browser, "http://www.google.com/bot.html")){
            unset($this->Browser);
        }
        elseif (strpos($this->Browser, 'Edge')){
            $this->Browser = 'Edge';
        }    
        elseif(strpos($this->Browser, 'OPR/')){
            $this->Browser = 'Opera';
        }
        elseif(strpos($this->Browser, 'Chrome')){
            $this->Browser = 'Chrome';
        }
        elseif(strpos($this->Browser, 'Firefox')){
            $this->Browser = 'Firefox';
        }
        elseif(strpos($this->Browser, 'Safari')){
            $this->Browser = 'Safari';
        }
        elseif(strpos($this->Browser, 'MSIE') || strpos($this->Browser, 'Trident/')){
            $this->Browser = 'IE';
        }
        else{
            unset($this->Browser);
        }
    }
    
    //atualiza tabela com dados de navegadores
    private function BrowserUpdate(){
        if(isset($this->Browser) && $this->Browser){
            $readAgent = new \_app\Conn\Read;
            $readAgent->ExeRead('ws_siteviews_agent', 'WHERE agent_name = :agent', "agent={$this->Browser}");
            if(!$readAgent->getResult()){            
                $ArrAgent = ['agent_name' => $this->Browser, 'agent_views' => 1, 'agent_lastview' => date("Y-m-d H:i:s")];
                $createAgent = new \_app\Conn\Create;
                $createAgent->ExeCreate('ws_siteviews_agent', $ArrAgent);
            }
            else{
                $ArrAgent = ['agent_views' => $readAgent->getResult()[0]['agent_views'] + 1];
                $updateAgent = new \_app\Conn\Update;
                $updateAgent->ExeUpdate('ws_siteviews_agent', $ArrAgent, 'WHERE agent_name = :name', "name={$this->Browser}");
            }
        }
    }

    /*
     * ****************************************
     * **********  USUÁRIOS ONLINE   **********
     * ****************************************
     */
    
    //cadastra o usuário online na tabela
    private function setUsuario(){
        if(isset($this->Browser) && $this->Browser){
            $sesOnline = $_SESSION['useronline'];
            $sesOnline['agent_name'] = $this->Browser;
            $sesOnline['online_url'] = $_SESSION['useronline']['online_url'];
            $sesOnline['online_url'] = $_SESSION['useronline']['online_url'];

            $userCreate = new \_app\Conn\Create;
            $userCreate->ExeCreate('ws_siteviews_online', $sesOnline);
        }
    }
    
    //atualiza navegação do usuário online
    private function UsuarioUpdate(){
        if(isset($this->Browser) && $this->Browser){
        if(strpos($_SESSION['useronline']['online_url'], ".")):
            $ArrOnline = [
            'online_endview' => $_SESSION['useronline']['online_endview'],
            'online_url' => $_SESSION['useronline']['online_url']
        ];
        else:
            $ArrOnline = [
            'online_endview' => $_SESSION['useronline']['online_endview'],
            'online_url' => $_SESSION['useronline']['online_url']
        ];
        endif;
        
        $userUpdate = new \_app\Conn\Update;
        $userUpdate->ExeUpdate('ws_siteviews_online', $ArrOnline, "WHERE online_session = :ses", "ses={$_SESSION['useronline']['online_session']}");
        
        if(!$userUpdate->getRowCount()){
            $readSes = new \_app\Conn\Read;
            $readSes->ExeRead('ws_siteviews_online', "WHERE online_session = :onses", "onses={$_SESSION['useronline']['online_session']}");
            if(!$readSes->getRowCount()){
                $this->setUsuario();
            }
        }
        }
    }
    
}
