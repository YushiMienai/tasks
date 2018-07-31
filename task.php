<?php
/**
 * Created by PhpStorm.
 * User: Yushi
 * Date: 28.07.2018
 * Time: 2:30
 */

class Task extends dbconnection
{
    private $conn;
    private $table = 'task';

    public function __construct() {
        $dbcon = new parent();
        $this->conn = $dbcon->connect();
    }

    private function find($where = null, $other = null){


        if($where != null ){
            $where_ = array();
            foreach($where as $k => $v){
                //die(var_dump($v));
                if ($v == array()) die;
                $where_[] = "$k = '$v'";
            }
            $where = 'where ' . implode(" and ", $where_);
        }

        //die(var_dump("SELECT * FROM  " . $this->table . " $where $other"));

        $select = mysqli_query($this->conn,"SELECT * FROM  " . $this->table . " $where $other") or die(mysqli_error($this->conn));

        return mysqli_fetch_all($select);
    }

    public function count(){
        $count = mysqli_fetch_all(mysqli_query($this->conn, "select count(*) from ".$this->table));
        return $count[0][0];
    }

    public function findById($id){
        return $this->find(['id' => $id]);
    }

    private function insert($data){

        $names = $values = array();
        foreach($data as $k => $v){
            $names[] = $k;
            $values[] = "'". $v . "'";
        }

        $names = implode(", ", $names);
        $values = implode(", ", $values);

        if (mysqli_query($this->conn, "insert into " . $this->table . " ($names) values ($values)") !== false) return mysqli_insert_id($this->conn);
        else return mysqli_error($this->conn);
    }

    private function update($id, $data){
        $set = array();
        foreach($data as $k => $v){
            if (trim($k) == '' || trim($v) == '') continue;
            $set[] = "$k = '$v'";
        }
        if (count($set) == 0) return false;
        $set = implode(", ", $set);

        if (mysqli_query($this->conn, "update " . $this->table . " set " . $set . " where id = '$id'")) return true;
        else return mysqli_error($this->conn);
    }

    public function findAll($cond = null, $other = null){
        $where = '';

        if ($cond != null) {
            foreach($cond as $v){
                $where .= $v[0] . "='" . $v[1]. "'";
            }
        }

        $query = $this->find($where, $other);

        return $query;
    }

    public function save($id, $data, $picture = null){

        $data_ = array();
        foreach($data as $k => $v){
            $data_[$k] = trim(strip_tags($v));
        }

        if ($id > 0){
            $this->update($id, $data_);

            $task = $this->findById($id);

            if (isset($picture["name"]) && trim($picture["name"]) != '' && $picture['size'] > 0 && $picture['error'] == 0 && trim($task[0][4]) != ''){
                unlink($task[0][4]);
            }
        }
        else {
            $id = $this->insert($data_);
        }


        if (isset($picture["name"]) && trim($picture["name"] && $picture['size'] > 0 && $picture['error'] == 0) != ''){
            $path_parts = pathinfo($picture["name"]);
            $ext = $path_parts["extension"];
            $pic = "images/" . $id . "." . $ext;
            if (move_uploaded_file($picture['tmp_name'],$pic)) $this->update($id, array('pic' => $pic));
        }

        return $id;
    }
}