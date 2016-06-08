<?php
namespace KsaR\Components;

class Cache
{
    /**
    * Path for cache.
    *
    * @var string
    */
    private $path = './';

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
    * @param string $path path for cache.
    * @param string $ext optional extension for cache.
    * @param bool $compress compression gzip enable.
    */
    public function __construct(STRING $path, STRING $ext, BOOL $compress = false)
    {
        if (isset($path)) {
            if (file_exists($path)) {
                $this->path = $path;
            } else {
                throw new Exception('Path:'."\r\n".$path."\r\n".'Doesn\'t exists.');
            }
        } else {
            throw new Exception('Please specify path for cache.');
        }

        if (isset($ext)) {
            $this->ext = $ext;
        }

        if ($compress) {
            if (!extension_loaded('zlib')) {
                throw new Exception('Please install or enable "zlib" libary, if you want use compression mode.');
            }

            $this->ext = $this->ext.'.gz';
            $this->compress = true;
        }
    }

    /**
    * Insert/Update the cache.
    *
    * @param string $key cache key.
    * @param string $value cache value.
    */
    public function set(STRING $key, STRING $value)
    {
        file_put_contents($this->path.$key.$this->ext, ($this->compress ? gzencode($value, 9) : $value));
    }

    /**
    * Display value of cache.
    *
    * @param string $key cache key.
    * @return string cache value.
    */
    public function get(STRING $key): STRING
    {
        $value = file_get_contents($this->path.$key.$this->ext);

        return $this->compress ? gzdecode($value) : $value;
    }

    /**
    * Validate cache.
    *
    * @param string $key cache key.
    * @param int $time time in seconds that cache will be max valid.
    * @return bool.
    */
    public function valid(STRING $key, INT $time): BOOL
    {
        $path = $this->path.$key.$this->ext;

        return file_exists($path) && $_SERVER['REQUEST_TIME']-filemtime($path) < $time;
    }
}
