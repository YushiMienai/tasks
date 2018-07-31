<?php
/**
 * Created by PhpStorm.
 * User: Yushi
 * Date: 28.07.2018
 * Time: 12:16
 */

class router
{
// Хранит конфигурацию маршрутов.
    /*private $routes;

    function __construct($routesPath){
        // Получаем конфигурацию из файла.
        $this->routes = include($routesPath);
    }*/

    // Метод получает URI. Несколько вариантов представлены для надёжности.
    function getURI(){
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        if(!empty($_SERVER['PATH_INFO'])) {
            return trim($_SERVER['PATH_INFO'], '/');
        }

        if(!empty($_SERVER['QUERY_STRING'])) {
            return trim($_SERVER['QUERY_STRING'], '/');
        }
    }

    function getParams(){
        $get_array = array();
        if(!empty($_SERVER['REQUEST_URI'])){
            //parse_str($_SERVER['REQUEST_URI'], $get_array);
            $array = explode("/", $_SERVER['REQUEST_URI']);

            for($i = 3; $i < count($array); $i = $i + 2){
                $var = false;
                if (isset($array[$i + 1])) $var =  $array[$i + 1];
                $get_array[$array[$i]] = $var;
            }
        }

        return $get_array;
    }

    function run(){

        $uri = explode("/", $this->getURI());

        if (!isset($uri[1])) $uri[1] = 'index';

        $controller = "\\" . $uri[0] . "Controller";
        $modal = "\\" . $uri[0];
        $action = "action" . $uri[1];
        $params = $this->getParams();

        $controllerFile = ROOT . $controller.'.php';
        if(file_exists($controllerFile)){
            include($controllerFile);
        }

        $modalFile = ROOT . $modal.'.php';
        if(file_exists($modalFile)){
            include($modalFile);
        }

        /*echo $_SERVER['REQUEST_URI'];
        echo "<pre>";
        print_r($params);
        echo "</pre>";
        die;*/

        if(!is_callable(array($controller, $action))){
            //header("HTTP/1.0 404 Not Found");
            return;
        }

        // Вызываем действие контроллера с параметрами
        call_user_func_array(array($controller, $action), array('args' => $params));
    }
}