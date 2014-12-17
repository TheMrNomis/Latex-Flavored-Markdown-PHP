<?php
    namespace LFM;

    if(!isset($basedir))
        $basedir = "../";

    require $basedir."PHP/mathHandler.php";
    require $basedir."PHP/textHandler.php";
    require $basedir."PHP/titlePageHandler.php";

    $settings = \LFM\Utils\loadConfig($basedir."PHP/conf/settings.conf");

    for($i = 1; $i < $argc; $i++)
    {
        switch($argv[$i])
        {
            case '-q':
            case '--quiet':
                $config['quiet'] = true;
                break;
            case '-n':
            case '--nolatex':
                $config['latex'] = false;
                break;
        }
    }
    $origin_text = file_get_contents($basedir."example.lmd");
    $document = explode("++++", $origin_text, 2);

    $defaultConfig = \LFM\Utils\loadConfig($basedir."PHP/conf/default.conf");
    $documentConfig = \LFM\TitlePage\transform($document[0], $defaultConfig);

    $config = \LFM\Utils\loadConfig($basedir."PHP/conf/".strtolower($documentConfig['type']).".conf");

    //texte
    $document_text = explode("$$", $document[1]);
    for($i=0; $i<count($document_text); $i+=2)
    {
        $document_text[$i] = explode("$", $document_text[$i]);
        for($j=0; $j<count($document_text[$i]);$j+=2)
            $document_text[$i][$j] = \LFM\Text\transform($document_text[$i][$j], $config);
        for($j=1; $j<count($document_text[$i]);$j+=2)
            $document_text[$i][$j] = \LFM\Math\transform($document_text[$i][$j]);
        $document_text[$i] = implode("$",$document_text[$i]);
    }
    for($i=1; $i<count($document_text); $i+=2)
    {
        $document_text[$i] = \LFM\Math\transform($document_text[$i]);
    }
    $document_text = implode("$$",$document_text);
    $latex ='
\documentclass{'.$config['docType'].'}
\usepackage[francais]{babel}
\usepackage[T1]{fontenc}
\usepackage[gen]{eurosym}
\usepackage{mathtools}
\begin{document}'.
    $documentConfig['titlepage']."
    ".$document_text."
    \end{document}";

    //end
    print_r($latex);
?>
