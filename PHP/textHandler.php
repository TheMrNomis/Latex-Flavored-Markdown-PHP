<?php
    namespace LFM\Text;

    if(!isset($basedir))
        $basedir = "../";

    function transformStructures($subject, $config)
    {
        $subject = preg_replace("#\+-{1,}([^-].{0,})-{1,}\+#U", $config['title0'].'{$1}', $subject);//part
        //titles, underlined
        $subject = preg_replace("#(.{1,})\n={4,}\n#U", $config['title1'].'{$1}'."\n", $subject);
        $subject = preg_replace("#(.{1,})\n-{4,}\n#U", $config['title2'].'{$1}'."\n", $subject);
        $subject = preg_replace("#(.{1,})\n_{4,}\n#U", $config['title3'].'{$1}'."\n", $subject);
        //titles, #
        $subject = preg_replace("/#{5}(.{1,})\n/U", $config['title5'].'{$1}'."\n", $subject);
        $subject = preg_replace("/#{4}(.{1,})\n/U", $config['title4'].'{$1}'."\n", $subject);
        $subject = preg_replace("/#{3}(.{1,})\n/U", $config['title3'].'{$1}'."\n", $subject);
        $subject = preg_replace("/#{2}(.{1,})\n/U", $config['title2'].'{$1}'."\n", $subject);
        $subject = preg_replace("/#{1}(.{1,})\n/U", $config['title1'].'{$1}'."\n", $subject);

        //list (itemize)
        $subject_lines = explode("\n",$subject);
        $itemize_sublevel = -1;
        $itemize[0] = false;
        for($i = 0; $i < count($subject_lines); $i++)
        {
            $line_already_treated = false;
            $tline = trim($subject_lines[$i]);
            foreach(array("* ", "- ", "+ ") as $itemizeIndicator)
            {
                if(strpos($tline, $itemizeIndicator)===0)//if string starts with spaces then a * : itemize
                {
                    $line_already_treated = true;
                    $old_itemize_sublevel = $itemize_sublevel;
                    $itemize_sublevel = strpos($subject_lines[$i], $itemizeIndicator)/4;
                    $subject_lines[$i] = str_repeat(" ",($itemize_sublevel+1)*4).
                                            "\item ".substr($tline, strlen($itemizeIndicator));
                    if(!isset($itemize[$itemize_sublevel]) || !$itemize[$itemize_sublevel])
                    {
                        $subject_lines[$i] = str_repeat(" ",$itemize_sublevel*4)."\begin{itemize}\n".$subject_lines[$i];
                        $itemize[$itemize_sublevel] = true;
                    }
                    for($level = $itemize_sublevel+1; $level <= $old_itemize_sublevel; $level++)
                        if(isset($itemize[$level]) && $itemize[$level])
                        {
                            $subject_lines[$i] = str_repeat(" ",$level*4)."\end{itemize}\n".$subject_lines[$i];
                            $itemize[$level] = false;
                        }
                }
            }
            if(!$line_already_treated && isset($itemize[$itemize_sublevel]) && $itemize[$itemize_sublevel])
            {
                $subject_lines[$i] = $subject_lines[$i]."\n\end{itemize}";
                $itemize[$itemize_sublevel] = false;
            }
        }
        $subject = implode("\n", $subject_lines);

        return $subject;
    }

    function transformInline($subject, $config)
    {
        $subject = str_replace("\n", " \n", $subject);
        $subject = preg_replace("#(\s)_([^_].{0,})_(\sw)#U", '$1\textit{$2}$3', $subject); //italic
        $subject = preg_replace('#(\s)__([^_].{0,})__(\s)#U', '$1\textbf{$2}$3', $subject); //bold
        $subject = str_replace(" \n", "\n", $subject);
        return $subject;
    }

    function transformAccents($subject, $config)
    {
        $lines = file($GLOBALS['basedir']."PHP/db/accentsReplacementList", FILE_IGNORE_NEW_LINES);
        foreach($lines as $line)
        {
            if(strpos($line, "@") !== false && $line[0] != "#")
            {
                $line = str_replace(" ","",$line);
                $strippedLine = explode("#",$line); //comment gestion
                list($accent, $latex) = explode("@", $strippedLine[0]);

                $subject = str_replace($accent, "{".$latex."}", $subject);
            }
        }
        return $subject;
    }

    function transform($subject, $config)
    {
        $subject = \LFM\Text\transformAccents($subject, $config);
        $subject = \LFM\Text\transformStructures($subject, $config);
        $subject = \LFM\Text\transformInline($subject, $config);
        return $subject;
    }
?>
