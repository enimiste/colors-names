<?php


$url = 'http://www.w3schools.com/colors/colors_names.asp';
$output = __DIR__ . '/output/colors_names.php';


function fetch_colors($content)
{
    if (preg_match_all('#\?color=(\w+).*\n[^\?]*\?hex=([0-9A-F]+)#', $content, $matches)) {
        $names = $matches[1];
        $hex = $matches[2];

        return array_combine($names, $hex);
    } else return [];
}

$php = <<<EOP
	<?php

	return [

EOP;

$content = file_get_contents($url);
$colors = fetch_colors($content);

$php .= array_reduce(array_keys($colors), function ($carry, $name) use ($colors) {
    return $carry . "['name'=>'$name',  'hex'=>'#{$colors[$name]}'],\n";
});

$php .= '];';

file_put_contents($output, $php);