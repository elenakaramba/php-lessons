<?php
$text = "There are;many|variations of:passages of Lorem Ipsum available,but the/majority have\"suffered|alteration in some form,by injected humour,or randomised words which don't look even.slightly:believable./";

$delimiter = array(" ",",",".","'","\"","|","\\","/",";",":");
$replace = str_replace($delimiter, $delimiter[0], $text);
$explode = explode($delimiter[0], $replace);

echo '<pre>';
print_r($explode);
echo '</pre>';
// replaces many symbols in text, then explodes it
?>