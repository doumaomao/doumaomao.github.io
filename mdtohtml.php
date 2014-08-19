<?php

require_once('Parsedown.php');
$Parsedown = new Parsedown();
    
$md_file = "index.md";
$md_path = "./$md_file";
if ( !is_file($md_path) )
{
    return false;
}
$markdown = file_get_contents($md_path);
$res = "<html>";
$res .= "<head>";
$res .= "<title>test</title>";
$res .= "<link href=\"https://github.com/doumaomao/doumaomao.github.io/blob/master/index.css\" rel=\"stylesheet\">";
$res .= "</head>";
$res .= "<body>";


$res .= $Parsedown->text($markdown);
$res .= "<body>";
$res .= "</html>";


$filename="index1.html";
$fh = fopen($filename, "w");
fwrite($fh, $res);
fclose($fh);
?>
