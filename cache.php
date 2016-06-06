<?php
require './class.Cache.php';

use KsaR\Components\Cache;

$cache = new Cache('cache/', '.cache', true);

if ($cache->valid('number', 30)) { // cache has up to 30s ?
    echo 'Cached: ', $cache->get('number'); // if yes, display.
} else { // else, insert new.
    $html = '<p>Welcome ! Lucky number is: '.random_int(0, 100).'.</p>';

    echo $html;
    $cache->set('number', $html);
}
