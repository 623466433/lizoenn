<?php
namespace lizoenn;

class Di
{
    protected $container;
    protected static $instance;
    protected function __construct()
    {
    }

    /**
     * Di实例
     * @return mixed
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
    /**
     * 绑定到容器
     * @param $name
     * @param $bind
     * @return bool
     */
    public function bind($name, $bind, $params = [])
    {
        if (is_string($bind) && class_exists($bind)) {
            $this->container[$name] = $this->build($bind, $params);
        } else {
            $this->container[$name] = $bind;
        }
        return true;
    }

    /**
     * 获取容器内容
     * @param $name
     * @return mixed
     */
    public function get($name, $params = [])
    {
        // TODO: Implement get() method.
        if (is_string($name) && class_exists($name)) {
            $this->container[$name] = $this->build($name, $params);
        }
        if (!isset($this->container[$name])) {
            return false;
        }

        return $this->container[$name];
    }
    /**
     * 实例化类
     *
     * @param [type] $class
     * @param array $params
     * @return void
     */
    protected function build($class, $params = [])
    {
        $dependencies = [];

        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if ($constructor != null) {
            $constructorParams = $constructor->getParameters();
            if ($constructorParams != null) {
                foreach ($constructorParams as $param) {
                    $paramClass = $param->getClass();
                    if ($paramClass != null) {
                        $dependencies[] = $this->build($paramClass->getName());
                    }
                }
            }
        }

        foreach ($params as $index => $param) {
            $dependencies[$index] = $param;
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
