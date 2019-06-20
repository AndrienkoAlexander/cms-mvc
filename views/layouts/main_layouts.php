<!DOCTYPE html>
<html lang="ru">
  <head>    
  	<meta http-equiv="Content-Type" content="text/html">
	<meta charset="utf-8">
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
    <link rel="stylesheet" type="text/css" href="/mvc/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/mvc/css/left-nav-style.css">
  </head>
  <body>
    <input type="checkbox" id="nav-toggle" hidden>
    <nav class="nav">
        <label for="nav-toggle" class="nav-toggle" onclick></label>
        <h2 class="logo"> 
            <a href="/mvc">Car News</a> 
        </h2>
        <ul>
            <li><a href="/mvc">Главная</a>
            <li><a href="/mvc/user/signup">Регистрация</a>
            <li><a href="/mvc/user/login">Вход</a>
            <li><a href="/mvc/category/carsCategories">Каталог машин</a>
            <li><a href="/mvc/admin/admin">Админ панель</a>
            <?php if(isset($_SESSION['username'])) {?>
            <li><a href="/mvc/admin/article/listArticlesAdmin">Edit Articles</a>
            <li><a href="/mvc/admin/category/listCategories">Edit Categories</a> 
            <li><a href="/mvc/admin/car/listCars">Edit Cars</a>
            <li><a href="/mvc/admin/comment/listComments">Edit Comments</a>
            <li><a href="/mvc/admin/user/listUsers">Users</a>
            <?php } ?>
        </ul>
    </nav>
    <div id="container">

		<a href="/mvc"><img id="logo" src="/mvc/images/logo.jpg" alt="Cars News" /></a>
		
		<?php
			if(isset($_SESSION['username']))
			{
		?>
			<div id="adminHeader">
		        <h2>Cars News Admin</h2>
		        <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. <a href="/mvc/admin/admin/logout"?>Log Out</a></p>
		    </div>
		<?php
			}
			else if(isset($_SESSION['user']))
			{
		?>
		    <div id="userHeader">
		        <p>You are logged in as <b><?php echo htmlspecialchars( $_SESSION['user']) ?></b>. <a href="/mvc/user/logout"?>Log Out</a></p>
		    </div>
		<?php
			}
			include ($contentPage);
		?>
		
		<div id="footer">
			Cars News &copy; 2019. All rights reserved.
		</div>
    </div>
  </body>
</html>

