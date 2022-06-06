<?php

if(!isset($_SERVER["HTTPS"])) $_SERVER["HTTPS"] = "off";

if ($_SERVER["HTTPS"] != "on" && $config["https"] && isset($_SERVER["HTTP_HOST"])) {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit;
}
