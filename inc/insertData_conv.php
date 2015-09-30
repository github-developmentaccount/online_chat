<?php
require_once 'config.php';
if(isset($_POST['message']) and isset($_POST['ch_id'])) {
    $st = new Chat();
    
    $ch_id = (preg_match('/^[1-9]{1,11}$/',$_POST['ch_id'])) ? $_POST['ch_id'] : false;
    $message = trim($_POST['message']);
    if($ch_id && $message != '') {
       $st->newReply($ch_id, $message);
    }
    else {
        echo 0;
    }
   
    
    
    
} else echo 0;