<?php 
require_once 'config.php';

$q = new Chat();
if(isset($_POST['user_two']) and !empty($_POST['user_two'])){
    
    if($q->CreatePR($_POST['user_two'])) {
        echo 1;
    }
    else echo 0;
        
    
} else echo 0;

