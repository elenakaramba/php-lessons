<?php
//бизнес логика
include_once __DIR__ . "/../functions.php";

if (array_key_exists("name", $_GET)) {
    $name = $_GET["name"];
} else {
    $name = "";
}
$content = renderTemplate("template.php", ["name"=>$name]);
echo $content;
