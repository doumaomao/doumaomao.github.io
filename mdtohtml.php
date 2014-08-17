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
$res = $Parsedown->text($markdown);
var_dump($res);


$filename="index1.html";
$fh = fopen($filename, "w");
fwrite($fh, $res);
fclose($fh);
?>
