<?php
// контролер
Class Controller_Index Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$results = array();
		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "newUser" ) $results['statusMessage'] = "You have been successfully registered! Log in.";
		}

		$data = Model_Article::getList( HOMEPAGE_NUM_ARTICLES );
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Cars News";

		$this->template->vars('results', $results);
		$this->template->view('index');
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
			require( TEMPLATE_PATH . "/signupForm.php" );
		}
	}
	
}