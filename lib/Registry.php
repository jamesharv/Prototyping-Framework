<?php
class Registry
{
    protected static $instance;
    protected $namespace;
    protected $variables = array();

    protected function __construct()
    { 
        
    }

    public static function set($variable, $value)
    {
        self::getNamespaceInstance()->setVar($variable, $value);
    }

    public static function get($variable, $default='')
    {
        return self::getNamespaceInstance()->getVar($variable, $default);
    }

    public function setVar($variable, $value)
    {
        $this->variables[$this->namespace . ':' . $variable] = $value;
    }

    public function getVar($variable, $default='')
    {
        $key = $this->namespace . ':' . $variable;
        return isset($this->variables[$key]) ? $this->variables[$key] : $default;
    }

    public static function getNamespaceInstance($ns='')
    {
        $instance = self::getInstance();
        $instance->setNamespace($ns);
        return $instance;
    }

    public function setNamespace($ns)
    {
        $this->namespace = $ns;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new Registry();
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return self::getNamespaceInstance($method);
    }

    public static function clear()
    {
        self::getInstance()->clearVariables();
    }

    public function clearVariables()
    {
        $this->variables = array();
    }

    public function __get($var)
    {
        return $this->getVar($var);
    }

    public function __set($var, $val)
    {
        $this->setVar($var, $val);
    }
}