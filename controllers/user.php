<?php
// контролер
Class Controller_User Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$this->login();
	}

	function signup() {
		$results = array();
		$results['pageTitle'] = "User Login | Cars News";

		if ( isset( $_POST['signup'] ) ) {

			// Пользователь получает форму регистрации
			if( Model_User::getByName($_POST['name']) ) {
				$results['errorMessage'] = "This name is already registered. Please try again.";
				$this->template->vars('results', $results);
				$this->template->view('signupForm');
				return;
			}

			if( Model_User::getByEmail($_POST['email']) ) {
				$results['errorMessage'] = "This email is already registered. Please try again.";
				$this->template->vars('results', $results);
				$this->template->view('signupForm');
				return;
			}

			if($_POST['password1'] != $_POST['password2'] ) {
				$results['errorMessage'] = "Passwords do not match. Please try again.";
				$this->template->vars('results', $results);
				$this->template->view('signupForm');
				return;
			}

			$user = new Model_User;
			$user->storeFormValues( $_POST );
			$user->password = $_POST['password1'];
			$user->insert();
			header( "Location: /mvc/index?status=newUser" );

		} elseif ( isset( $_POST['cancel'] ) ) {
			// Пользователь сбросид результаты: возвращаемся к списку статей
			header( "Location: /mvc/" );
		} else {
			// Пользователь еще не получил форму: выводим форму
			$results['user'] = new Model_User;
			$this->template->vars('results', $results);
			$this->template->view('signupForm');
		}
	}

	function login() {
		$results = array();
		$results['pageTitle'] = "User Login | Cars News";

		if ( isset( $_POST['login'] ) ) {

			$email = $_POST['email'];
			$user = Model_User::getByEmail($email);
			// Пользователь получает форму входа: попытка авторизировать пользователя
			if ( $_POST['password'] == $user->password ) {
				// Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора

				if(isset($_SESSION['username']))
				{
					unset($_SESSION['username']);
				}

				$user->startSession();
				header( "Location: /mvc/" );
			} else {
      			// Ошибка входа: выводим сообщение об ошибке для пользователя
				$results['errorMessage'] = "Incorrect email or password. Please try again.";
				$this->template->vars('results', $results);
				$this->template->view('loginForm');
			}
		} else {
			// Пользователь еще не получил форму: выводим форму
			$this->template->vars('results', $results);
			$this->template->view('loginForm');
		}
	}

	function logout() {
		$name = $_SESSION['user'];
		$user = Model_User::getByName($name);
		$user->endSession();
		header( "Location: /mvc/" );
	}
	
	function listUsers() {
		$results = array();
		$data = Model_User::getList();
		$results['users'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Users";

		if ( isset( $_GET['error'] ) ) {
			if ( $_GET['error'] == "userNotFound" ) $results['errorMessage'] = "Error: User not found.";
		}

		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
			if ( $_GET['status'] == "userDeleted" ) $results['statusMessage'] = "User deleted.";
		}

		$this->template->vars('results', $results);
		$this->template->view('listUsers', 'admin');
	}

	function deleteUser() {

		if ( !$user = Model_User::getById( (int)$_GET['userId'] ) ) {
			header( "Location: /mvc/admin/user/listUsers?error=userNotFound" );
			return;
		}
		__autoload('model_comment');
		Model_Comment::deleteByUser($user->id);

		$user->delete();
		header( "Location: /mvc/admin/user/listUsers?status=userDeleted" );
	}
}