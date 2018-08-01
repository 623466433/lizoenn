<?php
/**
 * Created by PhpStorm.
 * User: lizoenn
 * Date: 2018/7/30
 * Time: 17:59
 */

namespace lizoenn;


class AutoLoad
{
    /**
     * 自动注册
     */
    public function autoRegister()
    {
        \spl_autoload_register([$this, 'registerHandler']);
    }

    /**
     * 自动注册方法
     * @param $classname
     * @throws \Exception
     */
    protected function registerHandler($classname)
    {
        list($prefix, $base_src) = $this->getNamespace($classname);
        if (!isset($this->container['appNamespace'][$prefix])) {
            throw new \Exception("the class [$classname] is not exist!");
        }
        foreach ($this->container['appNamespace'][$prefix] as $item) {
            $filename = ROOT . $item . '/' . $base_src . '.php';
            if (file_exists($filename)) {
                require_once $filename;
                unset($filename);
                return;
            }
        }
        throw new \Exception("the file [$filename] is not exist!");
    }

    /**
     * 获取命名空间
     * @param $classname
     * @return array
     */
    protected function getNamespace($classname)
    {
        $length = strpos($classname, '\\');
        $prefix = substr($classname, 0, $length + 1);
        $src = substr($classname, $length + 1);

        return [$prefix, $src];
    }

    /**
     * 添加命名空间
     * @param $prefix
     * @param $dir
     */
    public function addNamespace($prefix, $dir)
    {
        $this->container['appNamespace'][$prefix][] = trim($dir, '\\');
    }
}
