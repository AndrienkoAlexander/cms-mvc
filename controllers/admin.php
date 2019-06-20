<?php
// контролер
Class Controller_Admin Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$this->login();
	}
	
	function login() {
		$results = array();
		$results['pageTitle'] = "Admin Login | Cars News";

		if ( isset( $_POST['login'] ) ) {

			// Пользователь получает форму входа: попытка авторизировать пользователя
			if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {

				// Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
				$_SESSION['username'] = ADMIN_USERNAME;

				if(isset($_SESSION['user']))
				{
					unset($_SESSION['user']);
				}

				header( "Location: /mvc/admin/article/listArticlesAdmin" );

			} else {
				// Ошибка входа: выводим сообщение об ошибке для пользователя
				$results['errorMessage'] = "Incorrect username or password. Please try again.";
				$this->template->vars('categories_tree', $categories_tree);
				$this->template->view('loginForm');
			}
		} else {
			// Пользователь еще не получил форму: выводим форму
			$this->template->view('loginForm', 'admin');
		}
	}

	function logout() {
		unset( $_SESSION['username'] );
		header( "Location: /mvc/admin/admin" );
	}
}