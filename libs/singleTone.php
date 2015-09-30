<?php
class DB{
    private static $instance = NULL;
    private function __construct(){}
    private function __clone(){}
    public static function getInstance(){
        if(!self::$instance){
            self::$instance = new PDO("mysql:host=localhost;dbname=online_chat", 'root', 'root'); 
            self::$instance-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}