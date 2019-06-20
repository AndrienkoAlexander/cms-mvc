<?php
// контролер
Class Controller_Category Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$this->carsCategories();
	}
	
	function carsCategories() {
		$data = Model_Category::getList();
		$results['pageTitle'] = "Категории машин";
		$categories_tree = Model_Category::map_tree($data['results']);

		$this->template->vars('categories_tree', $categories_tree);
		$this->template->view('carsCategories');
	}

	function listCategories() {
		$results = array();
		$data = Model_Category::getList();
		$results['categories'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageTitle'] = "Cars Categories";

		if ( isset( $_GET['error'] ) ) {
			if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
			if ( $_GET['error'] == "categoryContainsCars" ) $results['errorMessage'] = "Error: Category contains carss. Delete the cars, or assign them to another category, before deleting this category.";
		}

		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
			if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
  		}

  		$this->template->vars('results', $results);
		$this->template->view('listCategories', 'admin');
	}


	function newCategory() {
		$results = array();
		$results['pageTitle'] = "New Car Category";
		$results['formAction'] = "newCategory";

		if ( isset( $_POST['saveChanges'] ) ) {
			// User has posted the category edit form: save the new category
			$category = new Model_Category;
			$category->storeFormValues( $_POST );
			$category->insert();
			header( "Location: /mvc/admin/category/listCategories?status=changesSaved" );
		} elseif ( isset( $_POST['cancel'] ) ) {
			// User has cancelled their edits: return to the category list
			header( "Location: /mvc/admin/category/listCategories" );
		} else {
			// User has not posted the category edit form yet: display the form
			$results['category'] = new Model_Category;
			$this->template->vars('results', $results);
			$this->template->view('editCategory', 'admin');
		}
	}


	function editCategory() {
		$results = array();
		$results['pageTitle'] = "Edit Car Category";
		$results['formAction'] = "editCategory";

		if ( isset( $_POST['saveChanges'] ) ) {
			// User has posted the category edit form: save the category changes

			if ( !$category = Model_Category::getById( (int)$_POST['categoryId'] ) ) {
				header( "Location: /mvc/admin/category/listCategories?error=categoryNotFound" );
				return;
			}

			$category->storeFormValues( $_POST );
			$category->update();
			header( "Location: /mvc/admin/category/listCategories?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ) {
			// User has cancelled their edits: return to the category list
			header( "Location: /mvc/admin/category/listCategories" );
		} else {
			// User has not posted the category edit form yet: display the form
			$results['category'] = Model_Category::getById( (int)$_GET['categoryId'] );
			$this->template->vars('results', $results);
			$this->template->view('editCategory', 'admin');
		}
	}


	function deleteCategory() {
		__autoload('model_car');
		if ( !$category = Model_Category::getById( (int)$_GET['categoryId'] ) ) {
			header( "Location: /mvc/admin/category/listCategories?error=categoryNotFound" );
			return;
		}

		$cars = Car::getList( 1000000, $category->id );

		if ( $cars['totalRows'] > 0 ) {
			header( "Location: /mvc/admin/category/listCategories?error=categoryContainsCars" );
			return;
		}

		$category->delete();
		header( "Location: /mvc/admin/category/listCategories?status=categoryDeleted" );
	}
}