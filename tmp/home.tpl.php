<?php include_once 'tmp/header.tpl.php'; ?>
<header>
    <div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Online Chat</a>
    <ul class="nav">
      <li><a href="#myModal" role="button" data-toggle="modal">Log in</a></li>
      <li><a href="#">Sign in</a></li>
      <li></li>
    </ul>
    </div>
</div>
</header>

<h1 align="center">WELCOME TO OUR ONLINE CHAT</h1>


  	<div class="alert alert-success" style="display: none;">Entered successfully</div>
  	<div class="alert alert-error" style="display: none;"></div>
<div class="container">
	<div class="row">
		<div class="span4"></div>
		<div class="span6">
			
    		<form class="form-horizontal">
		  <div class="control-group">
		    <label class="control-label" for="inputEmail">Login</label>
		    <div class="controls">
		      <input type="text" id="inputEmail" placeholder="Login">
		    </div>
		  </div>
		  <div class="control-group">
		    <label class="control-label" for="inputPassword">Password</label>
		    <div class="controls">
		      <input type="password" id="inputPassword" placeholder="Password">
		    </div>
		  </div>
		  <div class="control-group">
		    <div class="controls">
		      <button type="submit" class="btn" id="sign-button">Log in</button>
		    </div>
		  </div>
		</form>		
			
		</div>
		<div class="span3"></div>
	</div>
</div>

 



<?php include_once 'tmp/footer.tpl.php'; ?>
