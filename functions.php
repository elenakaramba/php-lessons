<?php

function renderTemplate(string $path, array $vars ): string
{
    extract($vars,EXTR_OVERWRITE);
    ob_start();
    include $path;
    $content = ob_get_contents();
    ob_end_clean();
    return $content;

}