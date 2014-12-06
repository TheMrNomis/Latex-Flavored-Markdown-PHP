#! /usr/bin/php
<?php
    include_once("mathHandler.php");

    $origin_text = file_get_contents("../example.lmd");
    $text = explode("$$", $origin_text);
    print_r($text[1]);
    echo("\n+----------------------------------------+\n");
    for($i=1; $i<count($text); $i+=2)
    {
        $text[$i] = Math::transform($text[$i]);
    }
    print_r($text[1]);
?>
