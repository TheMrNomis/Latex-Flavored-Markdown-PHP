<?php
    function getBooleanAt($haystack, $needle)
    {
        return ($haystack / pow(2,$needle)) % 2;
    }

    class Math
    {
        private static function tranformFunctions($subject)
        {
            $lines = file("functionsReplacementList", FILE_IGNORE_NEW_LINES);
            foreach($lines as $line)
            {
                $line = str_replace(" ","",$line);
                $line = str_replace("*","\*",$line);
                if($line[0] != "#")
                {
                    $subject = str_replace("\n", " \n", $subject); //line end gestion (add spaces before \n)

                    $strippedLine = explode("#",$line); //comment gestion
                    list($nb, $LFM, $latex) = explode("@", $strippedLine[0]);

                    $nb = intval($nb);
                    for($i = 0; $i < pow(2,$nb); $i++)
                    {
                        $LFM_tmp = $LFM;
                        for($j = 1; $j <= $nb; $j++)
                        {
                            if(getBooleanAt($i,$j-1) == 0)
                                $var = "\s{0,}\{(.+)\}\s{0,}";
                            else
                                $var = "\s{1,}(\S+)\s{1,}";
                            $LFM_tmp = str_replace("$".$j."", $var, $LFM_tmp);
                        }
                        $subject = preg_replace("#".$LFM_tmp."#U", " ".$latex." ", $subject);
                    }
                }
            }

            return $subject;
        }

        private static function transformSymbols($subject)
        {
            $lines = file("symbolsReplacementList", FILE_IGNORE_NEW_LINES);
            foreach($lines as $line)
            {
                $line = str_replace(" ","",$line);
                if($line[0] != "#")
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

        public static function transform($subject)
        {
            $subject = preg_replace("#\s(\S{1,})\*(\S{1,})\s#U", "{ $1 \\cdot $2 }", $subject);
            $subject = Math::transformSymbols($subject);
            $subject = Math::tranformFunctions($subject);
            return $subject;
        }
    }
?>
