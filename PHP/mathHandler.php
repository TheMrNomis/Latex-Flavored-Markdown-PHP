<?php
    namespace LFM\Math;

    if(!isset($basedir))
        $basedir = "../";

    require $basedir."PHP/utils.php";

    function tranformFunctions($subject)
    {
        $subject = str_replace("\n", " \n", $subject);
        $lines = file($GLOBALS['basedir']."PHP/db/functionsReplacementList", FILE_IGNORE_NEW_LINES);
        foreach($lines as $line)
        {
            $line = str_replace(" ","",$line);
            $line = str_replace("*","\*",$line);
            if($line[0] != "#" && strpos($line, "@") !== false)
            {
                $subject = str_replace("\n", " \n", $subject); //line end gestion (add spaces before \n)
                $subject = str_replace("  ", " ", $subject);

                $strippedLine = explode("#",$line); //comment gestion
                list($nb, $LFM, $latex) = explode("@", $strippedLine[0]);

                $nb = intval($nb);
                for($i = 0; $i < pow(2,$nb); $i++)
                {
                    $LFM_tmp = $LFM;
                    for($j = 1; $j <= $nb; $j++)
                    {
                        if(\LFM\utils\getBooleanAt($i,$j-1) == 0)
                            $var = "\s?\{(.+)\}\s?";
                        else
                            $var = "\s(\S+)\s";
                        $LFM_tmp = str_replace("$".$j."", $var, $LFM_tmp);
                    }
                    $subject = preg_replace("#".$LFM_tmp."#U", " ".$latex." ", $subject);
                }
            }
        }

        $subject = str_replace(" \n", "\n", $subject);
        return $subject;
    }

    function transformSymbols($subject)
    {
        $lines = file($GLOBALS['basedir']."PHP/db/symbolsReplacementList", FILE_IGNORE_NEW_LINES);
        foreach($lines as $line)
        {
            $line = str_replace(" ","",$line);
            if($line[0] != "#" && strpos($line, "@") !== false)
            {
                $subject = str_replace("\n", " \n", $subject); //line end gestion (add spaces before \n)

                $strippedLine = explode("#",$line); //comment gestion
                list($mode, $LFM, $latex) = explode("@", $strippedLine[0]);
                switch($mode)
                {
                    case("b"):
                        $LFM = " ".$LFM." ";
                    break;
                    case("l"):
                        $LFM = " ".$LFM;
                    break;
                    case("r"):
                        $LFM = $LFM." ";
                    break;
                }
                $latex = " ".$latex." ";
                $subject = str_replace($LFM, $latex, $subject);
            }
        }

        return $subject;
    }

    function transformStructures($subject)
    {
        return $subject; //TODO
    }

    function transform($subject)
    {
        $subject = " ".$subject." ";
        $subject = preg_replace("#\s(\S{1,})\*(\S{1,})\s#U", " { $1 \\cdot $2 } ", $subject); // a*b -> a . b
        $subject = \LFM\Math\transformStructures($subject);
        $subject = \LFM\Math\transformSymbols($subject);
        $subject = \LFM\Math\tranformFunctions($subject);
        $subject = \trim($subject);
        return $subject;
    }
?>
