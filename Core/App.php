<?php
/**
 * Created by PhpStorm.
 * User: lizoenn
 * Date: 2018/7/30
 * Time: 18:21
 */

namespace lizoenn;

class App
{
    public static function run()
    {
        //1. 启动自动加载
        require_once __DIR__ . "/AutoLoad.php";
        $autoload = new AutoLoad();
        $autoload ->addNamespace('lizoenn\\', 'Core\\');
        $autoload->autoRegister();
        Di::getInstance()->bind('app', new static());
    }
}