<?php
require_once 'config.php';

if(isset($_POST['ch_id']) && preg_match('/^[1-9]{1,11}$/', $_POST['ch_id'])) {
    
    $st = new Chat();
    if($st->DropChannel($_POST['ch_id'])) {
        echo 1;
    } else {
        echo 0;
    }
}