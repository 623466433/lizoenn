<?php
namespace lizoenn;

use lizoenn\Exception\FileException;

class Config
{
    protected static $instance;
    protected $config;
    protected $exclude = ['.', '..'];
    public function __construct()
    {
        //获取所有配置文件
        $config_files = $this->scandir();
        //获取所有配置文件的配置，文件名为该配置的键
        $this->config = $this->initConfig($config_files);
    }
    /**
     * 获取单例
     *
     * @return void
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    /**
     * 获取所有配置文件
     *
     * @return void
     */
    protected function scandir()
    {
        if (!is_dir(CONFIG_PATH)) {
            throw new FileException("the dir is not exist!");
        }
        $dirs = scandir(CONFIG_PATH);
        $config_files = [];
        foreach ($dirs as $dir) {
            if (\in_array($dir, $this->exclude)) {
                continue;
            };
            array_push($config_files, $dir);
        }

        return $config_files;
    }
    /**
     * 初始化配置
     *
     * @param [type] $config_files
     * @return void
     */
    protected function initConfig($config_files)
    {
        $config = [];
        foreach ($config_files as $file) {
            $arr = explode('.', $file);
            $filename = trim(CONFIG_PATH, '/') . "/" . $file;
            if (!is_file($filename)) {
                continue;
            }
            $tempConfig = require_once $filename;

            $config[$arr[0]] = $tempConfig;
        }

        return $config;
    }

    /**
     * 获取配置
     *
     * @param [type] $key
     * @return void
     */
    public function get($key)
    {
        $arr = explode('.', $key);
        if (!isset($arr[1])) {
            return $this->config[$arr[0]];
        } else {
            return $this->config[$arr[0]][$arr[1]];
        }
    }

    /**
     * 设置配置
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    public function set($key, $value)
    {
        $arr = explode('.', $key);
        if (!isset($arr[1])) {
            return $this->config[$arr[0]] = $value;
        } else {
            return $this->config[$arr[0]][$arr[1]] = $value;
        }
    }
}
