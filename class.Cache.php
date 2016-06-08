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
namespace KsaR\Components;

/**
 * Cache Class
 *
 * @category Cache
 * @package  KsaR_Components
 * @author   KsaR
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/KsaR99/php-cache/blob/master/class.Cache.php
 *
 */
class Cache
{
    /**
     * Path for cache.
     *
     * @var string
     */
    private $path;

    /**
     * Extension for cache.
     *
     * @var string
     */
    private $ext = '.txt';

    /**
     * Compression gzip enable.
     *
     * @var bool
     */
    private $compress = false;

    /**
     * Constructor
     *
     * @param $path path for cache.
     * @param $ext optional extension for cache.
     * @param $compress compression gzip enable.
     */
    public function __construct(STRING $path, STRING $ext, BOOL $compress)
    {
        if (isset($path)) {
            if (!file_exists($path)) {
                throw new \Exception('Path:'."\r\n".$path."\r\n".'Doesn\'t exists.');
            }
                $this->path = $path;
        } else {
            throw new \Exception('Please specify path for cache.');
        }

        if (isset($ext)) {
            $this->ext = $ext;
        }

        if (isset($compress) && $compress) {
            if (!extension_loaded('zlib')) {
                throw new \Exception('Please install or enable "zlib" libary, if you want to use compression mode.');
            }

            $this->ext = $this->ext.'.gz';
            $this->compress = true;
        }
    }

    /**
     * Insert/Update the cache.
     *
     * @param $key cache key.
     * @param $value cache value.
     */
    public function set(STRING $key, STRING $value)
    {
        file_put_contents($this->path.$key.$this->ext, ($this->compress ? gzencode($value, 9) : $value));
    }

    /**
     * Return contents cache.
     *
     * @param $key cache key.
     * @return cache value.
     */
    public function get(STRING $key): STRING
    {
        $value = file_get_contents($this->path.$key.$this->ext);

        return $this->compress ? gzdecode($value) : $value;
    }

    /**
     * Validate cache.
     *
     * @param $key cache key.
     * @param $time time in seconds that cache will be max valid.
     */
    public function valid(STRING $key, INT $time): BOOL
    {
        $path = $this->path.$key.$this->ext;

        return file_exists($path) && $_SERVER['REQUEST_TIME']-filemtime($path) < $time;
    }
}
