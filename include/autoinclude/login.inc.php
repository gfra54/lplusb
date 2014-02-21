<?php
if(isset($_GET['logout'])){
	$_SESSION['login']=false;
	redir('back');
}
if(isset($_POST['login'])){
	if($User = new Data('users',$_POST['login']['login'])) {
		if($User->data['pass']==$_POST['login']['pass']){
			$_SESSION['login']=$User->data;
			redir('back');
		}
	}
}
function isLogged(){
	return isset($_SESSION['login']) && $_SESSION['login']['login'];
}
function isAdmin(){
	return isset($_SESSION['login']) && $_SESSION['login']['admin'] == 1;
}
function checkLogin(){
	if(isset($_SESSION['login']) && $_SESSION['login']['login']) {
		return true;
	} else {
		showHeaderAdmin('Vous devez vous identifier');
		?>
		<form method="post">
			<h1>Identification</h1>
	        <div class="both"></div>
	        <div class="admin_c1">Login</div>
	        <div class="admin_c3"><input class="form" type="text" name="login[login]"></div>
	        <div class="both"></div>
	        <div class="admin_c1">Password</div>
	        <div class="admin_c3"><input class="form" type="password" name="login[pass]"></div>
	        <div class="both"></div>
	        <div class="admin_c1">&nbsp;</div>
	        <div class="admin_c2"><input type="submit" value="Log In"></div>
		</form>
		<?php
		showFooterAdmin();
		exit;	
	}

}

