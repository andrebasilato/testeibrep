<?php

// Correção por causa do Cloudflare
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
$ipUpdate = $_SERVER["REMOTE_ADDR"];
$ipsLiberados = ['191.52.252.178', ''];
