<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package przepisy
 */
?>

<?php 
include 'phpFunctions.php';
session_start();
	if(isset($_SESSION["result"]) && get_field("typ_strony") == "Login"){
		header("Location:http://przepisowo.epizy.com/");
	}

	if(isset($_SESSION["result"]) == false && get_field("typ_strony") == "User"){
		header("Location:http://przepisowo.epizy.com/");
	}

	if(isset($_SESSION["result"]) == false && get_field("typ_strony") == "Kalendarz"){
		header("Location:http://przepisowo.epizy.com/");
	}

	if(isset($_SESSION["result"]) == false && get_field("typ_strony") == "Lista zakupów"){
		header("Location:http://przepisowo.epizy.com/");
	}

	if(get_field("typ_strony") == "Logout"){
		unset($_SESSION["result"]);
		header("Location:http://przepisowo.epizy.com/");
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= get_template_directory_uri() ?>/assets/css/style.css?v=11">
    <title>Przepisowo</title>
</head>
<body>
	<!-- header::begin -->
	<header class="np container-fluid d-flex justify-content-between bg-light">
	<nav class="navbar navbar-expand-lg navbar-light bg-light w-100 np">
  		<div class="logo-div d-flex align-items-center">
			<div class="logo ml-2"></div>
			<div class="title-div">Przepisowo.pl</div>
		</div>
  		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    	<span class="navbar-toggler-icon"></span>
  	</button>
  <div class="collapse navbar-collapse justify-content-end pr-2" id="navbarText">
    <ul class="navbar-nav">
	<?php
			if(isset($_SESSION["result"])){
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user'>Moje Konto - ".$_SESSION["result"]."</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user/addRecipe'>Dodaj przepis</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user/moje-przepisy'>Moje przepisy</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user/calendar?month=".ltrim(date('m'), "0")."'>Kalendarz</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user/dodaj-skladnik'>Składniki</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='".get_home_url()."/user/lista-zakupow'>Lista zakupów</a>";
				echo '</li>';
				echo '<li class="nav-item active">';
				echo "<a class='nav-link' href='przepisy/logout'>LOGOUT</a>";
				echo '</li>';
			}else{
				echo '<li class="nav-item active">';
				echo '<div class="logout"><a href="przepisy/login">LOGIN</a></div>';
				echo '</li>';
			}
			?>
    </ul>
  </div>
</nav>
	</header>
	<!-- header::end -->
	<?php wp_head(); ?>