<?php

require_once 'config.php';

$obj = new Chat();
if(isset($_POST) && !empty($_POST)){
	if(!$obj->MatchLogin($_POST['login'], $_POST['password'])){
		echo 0;
	} else
	{
		echo 1;
	}
}
else echo 0;