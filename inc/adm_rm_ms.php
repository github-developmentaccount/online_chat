<?php 
require_once 'config.php';

if(isset($_POST['m_id']) and !empty($_POST['m_id'])){
    $st = new Chat();
    if($st->RemoveMsg($_POST['m_id'])){
        echo 1;
    } else echo 0;
    
} 