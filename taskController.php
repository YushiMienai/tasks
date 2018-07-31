<?php
/**
 * Created by PhpStorm.
 * User: Yushi
 * Date: 28.07.2018
 * Time: 2:30
 */

class taskController {
    public function render($view, $args = null){
        foreach($args['args'] as $k => $v){
            $$k = $v;
        }

        require_once $view. ".php";

        return true;
    }

    private function isAdmin(){
        return (isset($_SESSION["login"]) && $_SESSION["login"] == 'admin');
    }

    private function getPostIntParam($param){
        $int = 0;
        if (isset($_POST[$param]) && (int)$_POST[$param] > 0) $int = (int)$_POST[$param];

        return $int;
    }

    public function actionIndex(){
        //self::render('view');
        require_once "view.php";
    }

    public function actionSave(){
        /*print_r($_POST);
        print_r($_FILES);*/

        $task = new Task();
        echo $task->save((int)$_POST['id'], $_POST, $_FILES['picture']);
    }

    public function actionShowOne(){
        $task = new Task();

        $id = self::getPostIntParam('id');

        $res = $task->findById($id);

        if (self::isAdmin()) $res = array('admin' => '1',$res[0]);
        else $res = array('admin' => '0', $res[0]);

        echo json_encode($res);
    }

    public function actionShowAll(){

        $task = new Task();

        $page = self::getPostIntParam('page');

        $sort = '';
        $order = '';
        if (isset($_POST['sort'])) $sort = trim(strip_tags($_POST['sort']));
        if (isset($_POST['order'])) $order = trim(strip_tags($_POST['order']));

        if (!in_array($sort, array('username', 'email', 'done'))) $sort = '';
        if (!in_array($order, array('asc', 'desc'))) $order = '';

        if ($sort != '') $orderby = " order by $sort $order";
        $limit = " limit $page, 3";

        $res = $task->findAll(null, $orderby.$limit);

        $pages = $task->count();
        $pages = ceil($pages / 3);


        if (self::isAdmin()) $res["admin"] = 1;
        else $res["admin"] = 0;
        $res["pages"] = $pages;

        echo json_encode($res);
    }

    public function actionLogin(){

        $login = $pass = '';

        if (isset($_POST['login'])) $login = trim(strip_tags($_POST['login']));
        if (isset($_POST['pass'])) $pass = trim(strip_tags($_POST['pass']));

        if ($login == 'admin' && $pass == '123'){
            $_SESSION['login'] = $login;
            echo "1";
        }
        else echo "0";
    }

    public function actionLogout(){
        unset($_SESSION['login']);

        echo "1";
    }

    public function actionGet(){
        $id = self::getPostIntParam('id');

        $task = new Task();

        $res = $task->findById($id);

        echo json_encode($res);
    }

    public function actionDone(){
        $id = self::getPostIntParam('id');
        $done = self::getPostIntParam('done');

        $task = new Task();
        if ($task->save($id, array('done' => $done)) == $id) return "1";
        else return "0";
    }

    public function actionPreview(){
        $picture = $_FILES['picture'];
        //print_r($picture);

        $data = array();
        foreach($_POST as $k => $v){
            $data[$k] = trim(strip_tags($v));
        }

        $res = $data;
        if (self::isAdmin()) $res["admin"] = 1;
        else $res["admin"] = 0;

        if(isset($picture["name"]) && trim($picture["name"]) != '' && $picture['size'] > 0 && $picture['error'] == 0){
            if(!is_dir("images")) mkdir("images") ;
            $path_parts = pathinfo($picture["name"]);
            $ext = $path_parts["extension"];
            $pic = "images/test." . $ext;
            if (move_uploaded_file($picture['tmp_name'],$pic)) $res['pic'] = $pic;
        }

        echo json_encode($res);
    }
}