<?php

namespace _app\Models;    

    if(!isset($permission_class) || empty($permission_class)):
        header("Location:http://www.colortek.com.br/404");
    endif;
?>
<?php

/**
 * Seo.class[ MODEL ]
 * Classe de apoio para o modelo link. Pode ser utilizada para gerar o SEO para as páginas do sistema.
 * @copyright (c) 2016, Guilherme Natus UPINSIDE TECNOLOGIA
 */
class Seo {
    
    private $File; // arquivo 
    private $Link; // artigo, categoria
    private $SearchAll;
    private $Data;
    private $Tags;
    
    /* DADOS POVOADOS */
    private $seoTags;
    private $seoData;
    
    function __construct($File, $Link, $SearchAll){
        $this->File = strip_tags(trim($File));
        $this->Link = strip_tags(trim($Link));
    }
    
    public function getTags(){
        $this->CheckData();
        echo $this->seoTags;
    }
    
    public function getData(){
        return $this->seoData;
    }
    
    //privates
    
    private function CheckData(){
        if(!$this->seoData){
            $this->getSeo();
        }
    }
    
    private function getSeo(){
        $ReadSeo = new \_app\Conn\Read;
        
        switch($this->File){
            
            //SEO::ARTIGO - POSTS DO SISTEMA!
            case 'artigo':
                $Admin = ((isset($_SESSION['userlogin']) && ($_SESSION['userlogin']['user_level'] == 3)) ? true : false);
                $Check = (($Admin) ? '' : 'post_status = 1 AND');

                $ReadSeo->ExeRead('ws_posts', "WHERE {$Check} post_name = :link", "link={$this->Link}");
                if(!$ReadSeo->getResult()){
                    $this->seoTags = null;
                    $this->seoData = null;
                } 
                else{
                    extract($ReadSeo->getResult()[0]);
                    $artigo = $ReadSeo->getResult()[0];
                    $ReadSeo->FullRead("SELECT user_name FROM ws_users WHERE user_id = :id", "id={$post_author}");
                    $userName = $ReadSeo->getResult()[0];
                    //$ReadSeo->FullRead("SELECT category_title FROM ws_categories WHERE category_id = :id", "id={$post_category}");
                    $this->seoData = array_merge($artigo, $userName);
                    $this->Data = [$post_title . ' - ' . NAME, $post_content, HOME . "/artigo/{$post_name}", HOME . "/uploads/" . $post_cover];
                    
                    // post:: post_views
                    $ArrUpdate = ['post_views' => $post_views + 1];
                    $update = new Update;
                    $update->ExeUpdate('ws_posts', $ArrUpdate, 'WHERE post_id = :id', "id={$post_id}");
                }
            break;
                
            //SEO::CATEGORIA
            case 'noticia':
                if(isset($this->Link) && !empty($this->Link)){
                    $ReadSeo->ExeRead('newsletters', "WHERE newsletter_name = :newsletter_name", "newsletter_name={$this->Link}");
                }
                else{
                    $ReadSeo->ExeRead('newsletters', "ORDER BY RAND()");
                }
                if(!$ReadSeo->getResult()){
                    $this->seoTags = null;
                    $this->seoData = null;
                }
                else{
                    if(isset($this->Link) && !empty($this->Link)){
                        extract($ReadSeo->getResult()[0]);
                        $read_newsletters_relateds = new \_app\Conn\Read;
                        $read_newsletters_relateds->ExeRead("newsletters", "WHERE newsletter_id != :newsletter_id", "newsletter_id={$ReadSeo->getResult()[0]['newsletter_id']}");
                        $this->seoData = array_merge($ReadSeo->getResult(), $read_newsletters_relateds->getResult());
                        $this->Data = ["Notícia " . $newsletter_title . ' - ' . NAME, $newsletter_description, HOME . "/noticia/{$newsletter_name}", INCLUDE_PATH . "/images/site.png"];
                    }
                    else{
                        $this->seoData = $ReadSeo->getResult();
                        $this->Data = ['Notícias - ' . NAME, "Confira as notícias da Colortek!", HOME . "/noticia", INCLUDE_PATH . "/images/site.png"];
                    }
                }
            break;
            
            //SEO:: CURRICULO    
            case 'curriculo':
                $this->Data = ["Anexe seu Currículo - " . NAME, "Interessado(a) em uma nova vaga de trabalho? Se inscreva para uma possível oportunidade anexando o seu currículo!", HOME . "/curriculo", INCLUDE_PATH . '/images/site.png'];
            break;

            //SEO:: INDEX    
            case 'index':
                $this->Data = [NAME, SITEDESC, HOME, INCLUDE_PATH . '/images/site.png'];
            break;
            
            //SEO:: DEFAULT - QUE VAI A 404, QUE É A PÁGINA DE ERRO. Está por dafault, por isso, se não cair em nenhum dos cases, cai na 404.
            default:
                $this->Data = [NAME . ' - 404 OOppss, nada encontrado!', SITEDESC, HOME . '/404', INCLUDE_PATH . '/images/site.png'];
                 // SITENAME - nome do site, SITEDESC descrição do site, HOME - página index, link para uma imagem que vai ser vinculada a essa página para que quando voce compartilhar isso em uma rede social!  
        }
        
        if($this->Data){
            $this->setTags();
        }
    }
    
    private function setTags(){
        $this->Tags['Title'] = $this->Data[0];
        $this->Tags['Content'] = \_app\Helpers\Check::Words(html_entity_decode($this->Data[1]), 25);
        $this->Tags['Link'] = $this->Data[2];
        $this->Tags['Image'] = $this->Data[3];
        
        array_map('strip_tags', $this->Tags);
        array_map('trim', $this->Tags);
        
        $this->Data = null;
        
        // NORMAL PAGE
        $this->seoTags = '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>' . "\n";
        $this->seoTags .= "\n";
        $this->seoTags .= '<link rel="shortcut icon" href="' . INCLUDE_PATH . '/images/favicon.png"/>' . "\n";
        $this->seoTags .= '<link rel="base" href="' . HOME . '"/>' . "\n";
        $this->seoTags .= '<link rel="alternate" type="application/rss+xml" title="' . FEEDTITLE . '" href="' . HOME . '/feed"/>' . "\n";
        $this->seoTags .= "\n";
        
        //HTML5 SHIV.JS
        $this->seoTags .= '<!--[if lt IE 9]><script src="' . HOME . '/_cdn/js/html5shiv.js"></script><![endif]-->' . "\n";
        $this->seoTags .= "\n";
        
        $this->seoTags .= '<title>' . $this->Tags['Title'] . '</title>' . "\n";
        $this->seoTags .= '<meta name="description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta name="robots" content="index, follow"/>' . "\n";
        $this->seoTags .= "\n";
        
        $this->seoTags .= '<link rel="author" href="' . AUTHOR . '"/>' . "\n";
        $this->seoTags .= '<link rel="publisher" href="' . PUBLISHER . '"/>' . "\n";
        $this->seoTags .= '<link rel="canonical" href="' . $this->Tags['Link'] . '"/>' . "\n";
        
        $this->seoTags .= '<meta itemprop="name" content="' . $this->Tags['Title'] . '"/>' . "\n";
        $this->seoTags .= '<meta itemprop="description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta itemprop="image" content="' . $this->Tags['Image'] . '"/>' . "\n";
        $this->seoTags .= '<meta itemprop="url" content="' . $this->Tags['Link'] . '"/>' . "\n";
        
        $this->seoTags .= "\n";
        
        // FACEBOOK
        $this->seoTags .= '<meta property="og:site_name" content="' . NAME . '"/>' . "\n";
        $this->seoTags .= '<meta property="og:locale" content="pt_BR"/>' . "\n";
        $this->seoTags .= '<meta property="og:title" content="' . $this->Tags['Title'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="og:description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="og:image" content="' . $this->Tags['Image'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="og:url" content="' . $this->Tags['Link'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="og:type" content="article"/>' . "\n";
        $this->seoTags .= '<meta property="article:author" content="' . FACEBOOK . '"/>' . "\n";
        $this->seoTags .= '<meta property="article:publisher" content="' . FACEBOOK . '"/>' . "\n";
        $this->seoTags .= "\n";
        
        // TWITTER
        $this->seoTags .= '<meta property="twitter:card" content="summary_large_image"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:site" content="' . TWITTER . '"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:domain" content="' . HOME . '"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:title" content="' . $this->Tags['Title'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:description" content="' . $this->Tags['Content'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:image" content="' . $this->Tags['Image'] . '"/>' . "\n";
        $this->seoTags .= '<meta property="twitter:url" content="' . $this->Tags['Link'] . '"/>' . "\n";
        
        $this->Tags = null;
    }
    
}

