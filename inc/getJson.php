<?php
require_once 'config.php';
$st = new Chat();
if(isset($_POST['channel']) || empty($_POST['channel'])) {
        $flag =  $st->showMessage($_POST['channel']);
        if($flag) {
            echo $flag;
        } else {
            echo 0;
        }
}
