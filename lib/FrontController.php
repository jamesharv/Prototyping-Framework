<?php
class FrontController
{
    protected static $_instance;
    protected $paths = array();
    protected $layoutName;
    protected $scope;

    protected function __construct()
    {
        $root = realpath(dirname(__FILE__) . '/../');
        $this->paths['root'] = $root;
        $this->setLayoutName('layout');
        $this->loadViewHelpers();
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)){
            self::$_instance = new FrontController();
        }
        return self::$_instance;
    }

    public function renderPage($viewName=false)
    {
        Registry::clear();
        $view = $this->loadView($viewName);
        Registry::getNamespaceInstance('layout')->setVar('view', $view);
        $layout = $this->loadLayout();

        echo $layout;
    }

    protected function getPath($name, $filename=false)
    {
        if (isset($this->paths[$name])){
            $path = $this->paths[$name];
        } else {
            $path = $this->paths['root'] . DIRECTORY_SEPARATOR . "$name";
        }
        return $filename ? $path . DIRECTORY_SEPARATOR . $filename : $path;
    }

    protected function loadView($viewName=false)
    {
        $this->setScope('views');
        $uri = preg_replace('/\.html$/', '', trim($viewName ? $viewName : $_SERVER['REQUEST_URI'], '/'));
        if (isset($_REQUEST['viewmenu']) || !$view = $this->loadPartial($uri, '.php')){
          $anchors = array_map(function($name){
            return '<a href="/' . $name . '">' . $name . '</a>';
          }, $this->getViewNames());
          $view = '<ul class="view-menu"><li>' . implode('</li><li>', $anchors) . '</li></ul>';
        }
        return $view;
    }

    protected function loadLayout()
    {
        $this->setScope('layout');
        $layout = $this->getLayoutName();
        return $this->loadPartial($layout, '.php');
    }

    protected function loadPartial($partialName, $ext='.phtml')
    {
        $file = $this->getPath($this->getScope(), "$partialName$ext");
        if (file_exists($file) && is_file($file)) {
            ob_start();
            include $file;
            return ob_get_clean();
        }
        return false;
    }

    public function setLayoutName($slug)
    {
        $this->layoutName = $slug;
    }

    public function getLayoutName()
    {
        return $this->layoutName;
    }

    protected function loadViewHelpers()
    {
        foreach(glob($this->getPath('views\\helpers', '*.php')) as $file) {
            require_once $file;
        }
    }

    public function getViewNames()
    {
        $views = array();
        foreach(glob($this->getPath('views\\*.php')) as $file) {
            $views[] = preg_replace('/.php$/', '', basename($file));
        }
        return $views;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function __get($var)
    {
        return Registry::getNamespaceInstance($var);
    }

    public function __call($method, $args)
    {
        $class = '\\ViewHelper\\' . ucfirst($method);
        if(class_exists($class)){
            $helper = call_user_func(array($class, 'getInstance'));
            return call_user_func_array(array($helper, 'invoke'), $args);
        }
        throw new Exception('There is no view helper called ' . $method);
    }
}
?>