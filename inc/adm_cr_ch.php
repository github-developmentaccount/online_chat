<?php 
require_once 'config.php';

if(isset($_POST['title']) && !empty(trim($_POST['title']))) {
    
    $title = trim($_POST['title']);
    $st = new Chat();
    
    if($st->CreateChannel($title)){
        echo 1;
    }else echo 0;

    
} else echo 0;