<?php
// контролер
Class Controller_Comment Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		include($this->template->view('comments'));
	}
	
	function listComments() {
		$results = array();
		$data = Model_Comment::getList();
		$results['comments'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Comments";

		if ( isset( $_GET['error'] ) ) {
			if ( $_GET['error'] == "commentNotFound" ) $results['errorMessage'] = "Error: User not found.";
		}

		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
			if ( $_GET['status'] == "commentShown" ) $results['statusMessage'] = "Comment is disabled/enabled.";
		}

		$this->template->vars('results', $results);
		$this->template->view('listComments', 'admin');
	}

	function shownComment() {

		if ( !$comment = Model_Comment::getById( (int)$_GET['commentId'] ) ) {
			header( "Location: /mvc/admin/comment/listComments?error=comentNotFound" );
			return;
		}

		$comment->invertShown();
		header( "Location: /mvc/admin/comment/listComments?status=comentShown" );
	}
}