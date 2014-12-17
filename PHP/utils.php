<?php
    namespace LFM\utils;

    if(!isset($basedir))
        $basedir = "../";;

    function getBooleanAt($haystack, $needle)
    {
        return ($haystack / pow(2,$needle)) % 2;
    }

    function loadConfig($name)
    {
        $confLines = file($name, FILE_IGNORE_NEW_LINES);
        $params = array();
        foreach($confLines as $confLine)
        {
            if(strpos($confLine, ":") !== false)
            {
                list($key, $value) = explode(":", $confLine, 2);
                $params[\trim($key)] = \trim($value);
            }
        }

        return $params;
    }
?>
