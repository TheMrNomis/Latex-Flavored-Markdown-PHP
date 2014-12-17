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
        return $subject;
    }

    function transformInline($subject, $config)
    {
        $subject = str_replace("\n", " \n", $subject);
        $subject = preg_replace("#\s_([^_].{0,})_\s#U", ' \textit{$1} ', $subject); //italic
        $subject = preg_replace('#\s__([^_].{0,})__\s#U', ' \textbf{$1} ', $subject); //bold
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
