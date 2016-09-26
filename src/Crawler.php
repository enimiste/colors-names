<?php


use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__ . '/../vendor/autoload.php';
$base_url = 'http://www.w3schools.com/colors/';
$url = 'colors_names.asp';
$output = __DIR__ . '/output/colors_names.php';


function fetch_colors($content)
{
    if (preg_match_all('#\?color=(\w+).*\n[^\?]*\?hex=([0-9A-F]+)#', $content, $matches)) {
        $names = $matches[1];
        $hex = $matches[2];

        return array_combine($names, $hex);
    } else return [];
}

/**
 * @param $url
 * @return array
 */
function crawl_w3school($url)
{
    echo 'Crawling ' . $url . PHP_EOL;
    $content = file_get_contents($url);
    $crawler = new Crawler($content);
    $content = $crawler->filter('.w3-table-all');
    if ($content->count() > 0) {
        $content = $content->first()->html();
        $colors = fetch_colors($content);
        $next_link = $crawler->filter('.nextprev > .w3-right');
        if ($next_link->count() > 0) {
            $next_link = $next_link->first()->attr('href');
        } else $next_link = '';
        echo count($colors) . ' found.' . PHP_EOL;
        return [$colors, $next_link];
    }
    return [[], ''];
}


$php = <<<EOP
	<?php

	return [

EOP;

$colors = [];
while (!empty($url)) {
    list($res, $url) = crawl_w3school($base_url . $url);
    $colors = array_merge($colors, $res);
}
$php .= array_reduce(array_keys($colors), function ($carry, $name) use ($colors) {
    return $carry . "['name'=>'$name',  'hex'=>'#{$colors[$name]}'],\n";
});

$php .= '];';

file_put_contents($output, $php);

echo 'End.' . PHP_EOL;