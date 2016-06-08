<?php
/**
* Cache system
*
* PHP version 7
*
* @category Cache
* @package  KsaR_Components
* @author   KsaR
* @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
* @link     https://github.com/KsaR99/php-cache
*
*/
require './class.Cache.php';

use KsaR\Components\Cache;

$cache = new Cache('cache/', '.cache', true);

if ($cache->valid('number', 30)) { // cache has up to 30s ?
    echo $cache->get('number'); // if yes, display.
} else { // else, save new.
    $html = '<p>Welcome ! Lucky number is: '.random_int(0, 100).'.</p>';

    echo $html;
    $cache->set('number', $html);
}
