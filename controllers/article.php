<?php
// контролер
Class Controller_Article  Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$this->listArticles();
	}

	function archive() {
		$results = array();
		$data = Model_Article::getList();
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Article Archive | Cars News";
		
		$this->template->vars('results', $results);
		$this->template->view('archive');
	}
	
	function listArticles() {
		$results = array();
		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "newUser" ) $results['statusMessage'] = "You have been successfully registered! Log in.";
		}

		$data = Model_Article::getList( HOMEPAGE_NUM_ARTICLES );
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Cars News";

		$this->template->vars('results', $results);
		$this->template->view('listArticles');
	}

	function viewArticle() {
		if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
			$this->listArticles();
			return;
		}

		$results = array();
		$results['article'] = Model_Article::getById( (int)$_GET["articleId"] );
		$results['pageTitle'] = $results['article']->title . " | Cars News";

		$this->template->vars('results', $results);
		$this->template->view('viewArticle');
	}

	function newArticle() {

		$results = array();
		$results['pageTitle'] = "New Article";
		$results['formAction'] = "newArticle";

		if ( isset( $_POST['saveChanges'] ) ) {

			// Пользователь получает форму редактирования статьи: сохраняем новую статью
			$article = new Model_Article;
			$article->storeFormValues( $_POST );
			$article->insert();
			header( "Location: /mvc/admin/article/listArticlesAdmin?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ) {
			// Пользователь сбросид результаты редактирования: возвращаемся к списку статей
			header( "Location: /mvc/admin/article/listArticlesAdmin" );
		} else {
		// Пользователь еще не получил форму редактирования: выводим форму
			$results['article'] = new Model_Article;
			$this->template->vars('results', $results);
			$this->template->view('editArticle', 'admin');
		}

	}

	function editArticle() {

		$results = array();
		$results['pageTitle'] = "Edit Article";
		$results['formAction'] = "editArticle";

		if ( isset( $_POST['saveChanges'] ) ) {

			// Пользователь получил форму редактирования статьи: сохраняем изменения

			if ( !$article = Model_Article::getById( (int)$_POST['articleId'] ) ) {
				header( "Location: /mvc/admin/article/listArticlesAdmin?error=articleNotFound" );
				return;
			}

			$article->storeFormValues( $_POST );
			$article->update();
			header( "Location: /mvc/admin/article/listArticlesAdmin?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ) {
			// Пользователь отказался от результатов редактирования: возвращаемся к списку статей
			header( "Location: /mvc/admin/article/listArticlesAdmin" );
		} else {

			// Пользвоатель еще не получил форму редактирования: выводим форму
			$results['article'] = Model_Article::getById( (int)$_GET['articleId'] );
			$this->template->vars('results', $results);
			$this->template->view('editArticle', 'admin');
		}
	}

	function listArticlesAdmin() {
		$results = array();
		$data = Model_Article::getList();
		$results['articles'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "All Articles";

		if ( isset( $_GET['error'] ) ) {
			if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
		}

		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
			if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
		}

		$this->template->vars('results', $results);
		$this->template->view('listArticles', 'admin');
	}

	function deleteArticle() {

		if ( !$article = Model_Article::getById( (int)$_GET['articleId'] ) ) {
			header( "Location: /mvc/admin/article/listArticlesAdmin?error=articleNotFound" );
			return;
		}

		$article->delete();
		header( "Location: /mvc/admin/article/listArticlesAdmin?status=articleDeleted" );
	}
}