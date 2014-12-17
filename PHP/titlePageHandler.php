<?php
namespace LFM\TitlePage;

    if(!isset($basedir))
        $basedir = "../";

    function transform($subject, $defaultConfig)
    {
        $paramText = explode("\n", $subject);
        $params = array();
        foreach($paramText as $param)
        {
            if(strpos($param, ":") !== false)
            {
                list($key, $value) = explode(":", $param, 2);
                $params[trim($key)] = \trim($value);
            }
        }

        foreach($defaultConfig as $k => $v)
            if(!isset($conf[$k]))
                $conf[$k] = $v;

        $conf['titlepage'] = '
    \begin{titlepage}
        \author{'.$params['author'].'}
        \title{'.$params['title'].'}
        '.((isset($params['date']))?('\date{'.$params['date'].'}'):'').'

        \maketitle
    \end{titlepage}
    ';
        return $conf;
    }
?>
