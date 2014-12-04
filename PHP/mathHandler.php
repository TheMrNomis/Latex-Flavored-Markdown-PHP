<?php
    class Math
    {
        static function transform($subject)
        {
            //remplacement
            $lines = file("mathReplacementList", FILE_IGNORE_NEW_LINES);
            foreach($lines as $line)
            {
                $line = str_replace(" ","",$line);
                if($line[0] != "#")
                {
                    $replacement = explode("@", $line);
                    $subject = str_ireplace(" ".$replacement[0]." ", " ".$replacement[1]." ", $subject);
                }
            }

            return $subject;
        }
    }
?>
