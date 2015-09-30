<?php 
require_once 'config.php';
$st = new Chat();
if(isset($_POST['channel']) && !empty($_POST['channel'])) {
    
   $res = $st->ExpandConversation($_POST['channel']);
        if($res) {
            echo $res;
            
        } else 
        {
            echo 0;
        }
    
    
} else echo 0;