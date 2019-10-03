<?php
    if(!isset($permission_files) || empty($permission_files)):
        header("Location:http://www.colortek.com.br/404");
    endif;
function SiteMap(){
    $home = HOME;
    $read = new _app\Conn\Read;
    $read->FullRead('SELECT newsletter_name FROM newsletters');
    $patch = getcwd();
    $patch = fopen($patch . DIRECTORY_SEPARATOR . 'sitemap.xml', 'w');
    $txt = "<?xml version='1.0' encoding='UTF-8'?>
            <?xml-stylesheet type='text/xsl' href='sitemap.xsl'?>
            <urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\r\n";
            $txt .= 
                "<url>\r\n    <loc>{$home}</loc>
                    <lastmod>".date('Y-m-d')."</lastmod>
                    <changefreq>Daily</changefreq>
                    <priority>1.0</priority>
                </url>\r\n";
            $txt .= 
                "<url>\r\n    <loc>{$home}/noticia</loc>
                    <lastmod>".date('Y-m-d')."</lastmod>
                    <changefreq>Daily</changefreq>
                    <priority>0.8</priority>
                </url>\r\n";    
    if($read->getResult()):
        foreach($read->getResult() as $results):
            $txt .=
                "<url>\r\n    
                    <loc>{$home}/noticia/{$results['newsletter_name']}</loc>
                    <lastmod>".date('Y-m-d')."</lastmod>
                    <changefreq>Weekly</changefreq>
                    <priority>0.8</priority>
                </url>\r\n";
        endforeach; 
    endif;
            $txt .=
                "<url>\r\n
                    <loc>{$home}/404</loc>
                    <lastmod>".date('Y-m-d')."</lastmod>
                    <changefreq>Monthly</changefreq>
                    <priority>0.3</priority>
                </url>\r\n";   
    $txt .= "</urlset>";
    fwrite($patch, $txt);
    fclose($patch);
}

