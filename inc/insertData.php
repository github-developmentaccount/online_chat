<?php
require_once 'config.php';
$obj = new Chat();

if(isset($_POST['message']) && isset($_POST['ch_id']))
        {
               $message = trim($_POST['message']);
               $channel = $_POST['ch_id'];
               
               if($message != '' && preg_match('/^[1-9]{1,11}$/', $channel)){
                   
                   if($obj->newMessage($message, $channel)) {
                       echo 1;
                   } else {
                       echo 0;
                   }
                   
               } else {
                   echo 0;
               }

      } 
