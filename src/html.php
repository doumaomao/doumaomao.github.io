<?php
require_once('conf.php');


$md = new mdfile();
$allmdfile = $md->getAllMdfiles();

foreach ($allmdfile as $md_name => $md_info)
{
			$res = "<html>";
			$res .= "\n";
			$res .= "<head>";
			$res .= "<title>test</title>";
			$res .= "<link href=\"https://github.com/doumaomao/doumaomao.github.io/blob/master/index.css\" rel=\"stylesheet\">";
			$res .= "</head>";
			$res .= "<body>";
			$markdown = $md->getMdtoHtml($md_name);
			$res .= "<div>$markdown</div>\n";
			$res .= "<body>";
			$res .= "\n";
			$res .= "</html>";
			file_put_contents("../{$md_name}.html", $res);
}
$html = "<html>";
$html .= "\n";
$html .= "<head>";
$html .= "<title>MYBlog</title>\n";
$html .= "<link href=\"http://libs.baidu.com/bootstrap/3.2.0/css/bootstrap.min.css\" rel=\"stylesheet\">";
$html .= "<script src=\"http://libs.baidu.com/jquery/2.0.0/jquery.min.js\"></script>\n";
$html .= "<script src=\"http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js\"></script>\n";
$html .= "</head>\n";
$html .= "<body>\n";
$html .= "<h2>Doumao's Blog</h2>";

foreach ($allmdfile as $md_name => $md_info)
{
        $title    = $md_info['title'];            
        $url      = "https://github.com/doumaomao/doumaomao.github.io/blob/master/{$md_name}.html";
		$html .= "<div class=\"page-header\">\n";
		$html .= "<h3>$title</h3>\n";
	    $html .= "</div>\n";
		$html .= "<p>$url</p>\n";
}
$html .= "<body>";
$html .= "\n";
$html .= "</html>";

        file_put_contents('../index.html', $html);
 
?>
