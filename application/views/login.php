<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login to CodeIgniter</title>

<style>
	p{
		color:red;
		font-size: 1.2em;
	}
</style>
</head>
<body>

<div id="container">
	<h1>Welcome to CodeIgniter!</h1>

	<?php 
		echo form_open('main/login_validation');
		echo "<label> Email: ";
		echo form_input('email', $this->input->post('email'));
		echo "</label> <br><br>";

		echo "<label> Password: ";
		echo form_password('password');
		echo "</label> <br><br>";


		echo "<label>";
		echo form_submit('login_submit','Login');
		echo "</label>";

		echo validation_errors();
		echo form_close();
	 ?>
	 <a href="<?php echo base_url().'main/signup';?>">Sign Up</a>
</div>

</body>
</html>