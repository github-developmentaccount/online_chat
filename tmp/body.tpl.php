<?php include_once 'tmp/header.tpl.php'; ?>
<?php if(isset($_SESSION['user']['username']) && !empty($_SESSION['user']['username'])):?>
<input type='hidden' name='origin' value="<?=$_SESSION['user']['role'];?>"></input>
<header>
    <div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Online Chat</a>
    <ul class="nav">
      <li><a href="?pc=true" role="button" data-toggle="modal">Item</a></li>
      <li class="dropdown" id="drop-list">
      	<a href="#" id="drop1" role="button" class="dropdown-toggle" data-toggle="dropdown">My rooms
			<b class="caret"></b>
      	</a>
      	<ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
            <?php
            $st = new Chat();
            $r = $st->ConvList();
            if($r != false && $r[0]['c_id'] != NULL){
            $result = '';
            foreach($r as $row) {
                $result .= '<li role="presentation"><a href="index.php?pc='.$row['c_id'].'" role="menuitem" tabindex="-1">'.$row['username'].'</a></li>';
            }
            echo $result;
            unset($st);
            }
            else {
                echo '<li role="presentation"><a href="#" role="menuitem" tabindex="-1">NOT FOUND</a></li>';
            }
            
            ?>

      	</ul>
      </li>
    </ul>
    <p class="navbar-text navbar-right" id="mark">Signed in as <?= $_SESSION['user']['username']; ?>&nbsp;&nbsp;<a href="?logout=true" class="navbar-link"> <i class="fa fa-sign-out"></i></a></p>

  </div>
</div>
</header>


 




<!-- BODY MESSAGES -->
<div class="container">
<div class="row">
  <div class="span4 fixed col">
	  
	  	<div class="sidebar">
<!--	  		<form>-->
			  <fieldset>
			    <legend align="center">Create a private conversation</legend>
			    <div class="wrapper-field">
                            <?php if($_SESSION['user']['role'] == 'admin'): ?>
			    <label for="room-name">Create channel with a title...</label>
			    <input type="text" class="room-name fix-form" id="ch_name" placeholder="Type here...">
                            <button class="btn" id="ch_btn">Create</button>
                            <br>
                            <hr>
                            <p>Create a private room</p>
                            <?php endif; ?>
                            
			    <button type="button" class="btn btn-primary btn-small" data-toggle="button" id="private">Choose a user</button>
			    <br>
			    <br>
			   
			    <select class="hide" multiple='multiplie'>
                                <?php 
                                          
                                    $st = new Chat();
                                    $stmt = $st->UsList();
                                    $chain = '';
                                    foreach($stmt as $row){
                                        $chain .= '<option value="' . $row[0].'">'.$row[1].'</option>';
                                    }
                                    echo $chain;
                                ?>
				</select>
				
			    <button type="submit" class="btn" id="p_room">Create</button>
			    </div>
			  </fieldset>
<!--			</form>-->
	  	</div>
  	
  </div>

  <div class="span8">
  	<div class="result-messages col" id="wrapper-mess">
  	<!--user message -->

  		
</div>


  		<!-- TEXTAREA TO SEND MESSAGES-->
			
		<textarea class="text-area" cols="3" rows="2"></textarea>
		<button class="btn btn-info" id="button-send">Send</button>
		<div class="alert alert-success"></div>
		<div class="alert alert-danger"></div>


  		<!-- END OF TEXTAREA -->

  	
  </div>


          <!-- SIDEBAR-->
	<div class="span3">
		<div class="sidebar-right-wrapper col">
		<h4 align="center">Chat Rooms</h4>
			<div class="wrapper-field">
                            <?php if($_SESSION['user']['role'] == 'admin') : ?>
				<ul class="nav nav-tabs nav-stacked" id="route">
                                    <?php 
                                    $s = new Chat();
                                    $st = $s->ChannelsList();
                                    $chain = '';
                                    foreach($st as $row){
                                        $chain .= '<li><a href="index.php?ch='.$row['channel_id'].'" class="stack">'.$row['title'].'</a><button class="delete list" id="'.$row['channel_id'].'"><i class="fa fa-times"></i></button></li>';
                                    }
                                    echo $chain;
                                    ?>
                                    
				</ul>
                            <?php else: ?>
				<ul class="nav nav-tabs nav-stacked" id="route">
                                    <?php 
                                    $s = new Chat();
                                    $st = $s->ChannelsList();
                                    $chain = '';
                                    foreach($st as $row){
                                        $chain .= '<li><a href="index.php?ch='.$row['channel_id'].'" class="stack">'.$row['title'].'</a></li>';
                                    }
                                    echo $chain;
                                    ?>
                                    
				</ul>
                            <?php endif;?>
			</div>	
		</div>
	</div>
        <!-- SIDEBAR-->
  
</div>
</div>
<!-- BODY MESSAGES -->


	<?php else:?>
	<?php include_once 'tmp/home.tpl.php'; ?>
	<?php endif;?>

	<?php include_once 'tmp/footer.tpl.php'; ?>
