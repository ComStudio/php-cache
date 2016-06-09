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
    private static $path;

    /**
     * Extension for cache.
     *
     * @var string
     */
    private static $extension;

    /**
     * Compression gzip enable.
     *
     * @var bool
     */
    private static $isCompressed = false;

    /**
     * Constructor
     *
     * @param string $path path for cache.
     * @param string $extension extension for cache.
     * @param bool $isCompressed compress compression gzip enable.
     * @throws \Exception
     */
    public function __construct(string $path, string $extension = '.dat', bool $isCompressed = false)
    {
        if (!isset($path) || !file_exists($path)) {
            throw new \InvalidArgumentException('The specified path does not exist.');
        }

        static::$path = $path;
        static::$extension = $extension;

        if ($isCompressed) {
            if (!extension_loaded('zlib')) {
                throw new \Exception('Please install or enable "zlib" libary, if you want to use compression mode.');
            }

            static::$extension = static::$extension.'.gz';
            static::$isCompressed = true;
        }
    }

    /**
     * Insert/Update the cache.
     *
     * @param string $key cache key.
     * @param string $value cache value.
     */
    public function set(string $key, string $value)
    {
        $path = static::fullPath($key);

        file_put_contents($path, (static::$isCompressed ? gzencode($value, 9) : $value));
    }

    /**
     * Return contents cache.
     *
     * @param string $key cache key.
     * @return string cache value.
     */
    public function get(string $key): string
    {
        $path = static::fullPath($key);

        $value = file_get_contents($path);

        return static::$isCompressed ? gzdecode($value) : $value;
    }

    /**
     * Validate cache.
     *
     * @param string $key cache key.
     * @param int $time time in seconds that cache will be max valid.
     * @return bool
     */
    public function valid(string $key, int $time): bool
    {
        $path = static::fullPath($key);

        return file_exists($path) && $_SERVER['REQUEST_TIME']-filemtime($path) < $time;
    }

    /**
     * @param string $key
     * @return string
     */
    private static function fullPath(string $key)
    {
        return static::$path.$key.static::$extension;
    }
}
