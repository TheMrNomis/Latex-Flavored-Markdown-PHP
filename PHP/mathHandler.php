<?php
    class Math
    {
        private static function transformSymbols($subject)
        {
            //remplacement
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
                    $subject = str_ireplace($LFM, $latex, $subject);
                }
            }

            return $subject;
        }

        public static function transform($subject)
        {
            $subject = Math::transformSymbols($subject);
            return $subject;
        }
    }
?>
