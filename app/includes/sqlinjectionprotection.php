<?php
foreach ($_GET as $ind => $valor) {
    if (!is_array($_GET[$ind])) {
        $_GET[$ind] = SQLInjectionProtection($valor);
    } else {
        $_GET[$ind] = array_map(SQLInjectionProtection, $valor);
    }
}

function SQLInjectionProtection($str, $charset = 'UTF-8')
{
    //Remove Null Characters
    $str = preg_replace('/\0+/', '', $str);
    $str = preg_replace('/(\\\\0)+/', '', $str);

    //Validate standard character entities
    $str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u', "\\1;", $str);

    //Validate UTF16 two byte encoding (x00)
    $str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu', "\\1\\2;", $str);

    //URL Decode
    $str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
    $str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);

    //Convert character entities to ASCII
    if (preg_match_all("/<(.+?)>/si", $str, $matches)) {
        for ($i = 0; $i < count($matches['0']); $i++) {
            $str = str_replace($matches['1'][$i], html_entity_decode($matches['1'][$i], ENT_COMPAT, $charset), $str);
        }
    }

    //Convert all tabs to spaces
    $str = preg_replace("#\t+#", " ", $str);

    //Makes PHP tags safe
    $str = str_replace(array('<?php', '<?PHP', '<?', '?>', '"'), array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;', '&quot;'), $str);

    //Compact any exploded words/
    $words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
    foreach ($words as $word) {
        $temp = '';
        for ($i = 0; $i < strlen($word); $i++) {
            $temp .= substr($word, $i, 1) . "\s*";
        }

        $temp = substr($temp, 0, -3);
        $str = preg_replace('#' . $temp . '#s', $word, $str);
        $str = preg_replace('#' . ucfirst($temp) . '#s', ucfirst($word), $str);
    }

    //Remove disallowed Javascript in links or img tags
    $str = preg_replace("#<a.+?href=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>.*?</a>#si", "", $str);
    $str = preg_replace("#<img.+?src=.*?(alert\(|alert&\#40;|javascript\:|window\.|document\.|\.cookie|<script|<xss).*?\>#si", "", $str);
    $str = preg_replace("#<(script|xss).*?\>#si", "", $str);

    //Remove JavaScript Event Handlers
    $str = preg_replace('#(<[^>]+.*?)(onblur|onchange|onclick|onfocus|onload|onmouseover|onmouseup|onmousedown|onselect|onsubmit|onunload|onkeypress|onkeydown|onkeyup|onresize)[^>]*>#iU', "\\1>", $str);

    //Sanitize naughty HTML elements
    $str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);

    //Sanitize naughty scripting elements
    $str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);

    //Final clean up
    $bad = array(
        'document.cookie' => '',
        'document.write' => '',
        'window.location' => '',
        "javascript\s*:" => '',
        "Redirect\s+302" => '',
        '<!--' => '&lt;!--',
        '-->' => '--&gt;'
    );

    foreach ($bad as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    //Apenas remover as barras se ele já foi reduzido em PHP
    if (get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    }

    $str = str_replace(array('`', '"', '?', '+', '{', '}', '\\'), array('&lsquo;', '&quot;', '', '', '', '', ''), $str);

    return $str;

}