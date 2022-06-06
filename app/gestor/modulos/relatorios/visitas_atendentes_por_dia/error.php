<?php

function throwError($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<style>
        .highlight .hll{background-color:#ffc}
        .highlight{background:#000}
        .highlight .c{color:#b729d9;font-style:italic}
        .highlight .err{color:#a40000;border:1px solid #ef2929}
        .highlight .g{color:#fff}
        .highlight .k{color:#ff8400}
        .highlight .l{color:#fff}
        .highlight .n{color:#fff}
        .highlight .o{color:#e0882f}
        .highlight .x{color:#fff}
        .highlight .p{color:#999}
        .highlight .cm{color:#b729d9;font-style:italic}
        .highlight .cp{color:#a0a0a0}
        .highlight .c1{color:#b729d9;font-style:italic}
        .highlight .cs{color:#b729d9;font-style:italic}
        .highlight .gd{color:#a40000}
        .highlight .ge{color:#fff;font-style:italic}
        .highlight .gr{color:#ef2929}
        .highlight .gh{color:#000080}
        .highlight .gi{color:#00a000}
        .highlight .go{color:#808080}
        .highlight .gp{color:#745334}
        .highlight .gs{color:#fff;font-weight:bold}
        .highlight .gu{color:#800080;font-weight:bold}
        .highlight .gt{color:#a40000;font-weight:bold}
        .highlight .kc{color:#ff8400}
        .highlight .kd{color:#ff8400}
        .highlight .kn{color:#ff8400}
        .highlight .kp{color:#ff8400}
        .highlight .kr{color:#ff8400}
        .highlight .kt{color:#ff8400}
        .highlight .ld{color:#fff}
        .highlight .m{color:#1299da}
        .highlight .s{color:#56db3a}
        .highlight .na{color:#fff}
        .highlight .nb{color:#fff}
        .highlight .nc{color:#fff}
        .highlight .no{color:#fff}
        .highlight .nd{color:#808080}
        .highlight .ni{color:#ce5c00}
        .highlight .ne{color:#c00}
        .highlight .nf{color:#fff}
        .highlight .nl{color:#f57900}
        .highlight .nn{color:#fff}
        .highlight .nx{color:#fff}
        .highlight .py{color:#fff}
        .highlight .nt{color:#ccc}
        .highlight .nv{color:#fff}
        .highlight .ow{color:#e0882f}
        .highlight .w{color:#f8f8f8;text-decoration:underline}
        .highlight .mf{color:#1299da}
        .highlight .mh{color:#1299da}
        .highlight .mi{color:#1299da}
        .highlight .mo{color:#1299da}
        .highlight .sb{color:#56db3a}
        .highlight .sc{color:#56db3a}
        .highlight .sd{color:#b729d9;font-style:italic}
        .highlight .s2{color:#56db3a}
        .highlight .se{color:#56db3a}
        .highlight .sh{color:#56db3a}
        .highlight .si{color:#56db3a}
        .highlight .sx{color:#56db3a}
        .highlight .sr{color:#56db3a}
        .highlight .s1{color:#56db3a}
        .highlight .ss{color:#56db3a}
        .highlight .bp{color:#3465a4}
        .highlight .vc{color:#fff}
        .highlight .vg{color:#fff}
        .highlight .vi{color:#fff}
        .highlight .il{color:#1299da}
        </style>
    <pre class=\"highlight\" style=\"background-color: #232125; overflow: auto; line-height: 1.3em; color: #fff; font-size: 14px; padding: .7em;\">
        <span class=\"ow\">WARNING</span>: [<span class=\"mi\">$errno</span>] $errstr
    </pre>\n";
        break;

    case E_USER_NOTICE:
        echo "<b>NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        // echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}