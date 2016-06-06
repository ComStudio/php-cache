<?php
namespace KsaR\Components;

class Cache
{
    /**
    * Variable with path for cache.
    *
    * @var string
    */
    private $CACHE_PATH = './';

    /**
    * Variable with extension for cache.
    *
    * @var string
    */
    private $EXT = '.txt';

    /**
    * Config for compression.
    *
    * @var bool
    */
    private $compress = false;

    /**
    * Constructor
    *
    * @param string $path directory for cache.
    * @param string $ext optional extension for cache.
    * @param bool $compress defines that using gzip compress or not.
    */
    public function __construct(STRING $path, STRING $ext, BOOL $compress = false)
    {
        if (isset($path)) {
            if (file_exists($path)) {
                $this->CACHE_PATH = $path;
            } else {
                throw new Exception('Path:'."\r\n".$path."\r\n".'Doesn\'t exists.');
           }
        } else {
            throw new Exception('Please specify path for cache.');
        }

        if (isset($ext)) {
            $this->EXT = $ext;
        }

        if ($compress) {
            if (!extension_loaded('zlib')) {
                throw new Exception('Please install or enable "zlib" libary, if you want use compression mode.');
            }

            $this->EXT = $this->EXT.'.gz';
            $this->compress = true;
        }
    }

    /**
    * Insert/Update the cache.
    *
    * @param string $key cache name.
    * @param string $value cache value.
    */
    public function set(STRING $key, STRING $value)
    {
        if ($this->compress) {
            $value = gzencode($value, 9);
        }

        file_put_contents($this->CACHE_PATH.$key.$this->EXT, $value);
    }

    /**
    * Display value of cache.
    *
    * @param string $key cache name.
    * @return string cache value.
    */
    public function get(STRING $key): STRING
    {
        $file = file_get_contents($this->CACHE_PATH.$key.$this->EXT);

        if ($this->compress) {
            $file = gzdecode($file);
        }

        return $file;
    }

    /**
    * Validate cache.
    *
    * @param string $key cache name.
    * @param int $time time in seconds that cache will be max valid.
    * @return bool.
    */
    public function valid(STRING $key, INT $time): BOOL
    {
        $path = $this->CACHE_PATH.$key.$this->EXT;

        return file_exists($path) && $_SERVER['REQUEST_TIME']-filemtime($path)<$time;
    }
}
