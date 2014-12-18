<?php
    namespace LFM;

    if(!isset($basedir))
        $basedir = "../";

    require $basedir."PHP/mathHandler.php";
    require $basedir."PHP/textHandler.php";
    require $basedir."PHP/titlePageHandler.php";

    $settings = array(
    'quiet' => false,
    'latex' => true,
    'term-output' => false
    );

    for($i = 1; $i < $argc; $i++)
    {
        switch($argv[$i])
        {
            case '-q':
            case '--quiet':
                $settings['quiet'] = true;
                break;
            case '-n':
            case '--nolatex':
                $settings['latex'] = false;
                break;
            case '-l':
            case '--latex':
                $settings['latex'] = true;
                break;
            case '-t':
            case '--term-output':
                $settings['term-output'] = true;
                break;
            case '-f':
            case '--file-output':
                $settings['term-output'] = false;
                break;
            case '-o':
            case '--output':
                $settings['output-file'] = $argv[++$i];
                break;
            case '-i':
            case '--input':
                $i++;
            default:
                $settings['input-file'] = $argv[$i];

        }
    }

    if(!isset($settings['input-file']))
    {
        echo("no file specified\n");
        exit;
    }
    if(!file_exists($settings['input-file']))
    {
        echo("no such file as ".$settings['input-file']."\n");
        exit;
    }

    $origin_text = file_get_contents($settings['input-file']);
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

    //output
    if(isset($settings['term-output']) && $settings['term-output'])
    {
        echo $latex;
    }
    else
    {
        if(!isset($settings['output-file']))
            $settings['output-file'] = "tex/LFMouput";
        if(!isset($settings['latex']))
            $settings['latex'] = true;

        $fileName = $basedir.$settings['output-file'].".tex";
        if(file_exists($fileName))
            unlink($fileName);
        $file = fopen($fileName, 'w');
        fwrite($file, $latex);
        fclose($file);

        if($settings['latex'])
        {
            if($settings['quiet'])
                exec(escapeshellcmd("pdflatex ".$settings['output-file'].".tex"));
            else
                system(escapeshellcmd("pdflatex -output-directory tex/ ".$settings['output-file'].".tex"));
        }
    }
#    print_r($latex);
?>
