<?php
__autoload('model_image');
__autoload('model_category');

// контролер
Class Controller_Car Extends Controller_Base {
	
	// шаблон
	public $layouts = "main_layouts";
	
	// экшен
	function index() {
		$this->viewCategory();
	}

	function viewCategory() {
		$results = array();
		$categoryId = ( isset( $_GET['categoryId'] ) && $_GET['categoryId'] ) ? (int)$_GET['categoryId'] : 2;
		$results['category'] = Model_Category::getById( $categoryId );
		$data = Model_Car::getList( 100000, $categoryId);
		$results['cars'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['pageHeading'] = $results['category'] ?  $results['category']->name : "Car Category";
		$results['pageTitle'] = $results['pageHeading'] . " | Cars News";
		$results['images'] = array();

		foreach ( $results['cars'] as $car ) {
			$temp = Model_Image::getByCarId($car->id);
			if($temp['totalRows'] > 0) {
				$results['images'][$car->id] = $temp['results'][0];
			}
		}

		$this->template->vars('results', $results);
		$this->template->view('viewCar');
	}

	function viewCar() {
		if ( !isset($_GET["carId"]) || !$_GET["carId"] ) {
			$this->viewCategory();
			return;
		}

		$results = array();
		$results['cars'] = array();
		$results['cars'][0] = Model_Car::getById( (int)$_GET["carId"] );
		$results['images'] = Model_Image::getByCarId( (int)$_GET["carId"] );
		$results['category'] = Model_Category::getById( $results['cars'][0]->categoryId );
		$results['pageTitle'] = $results['cars'][0]->name . " | Cars News";

		$this->template->vars('results', $results);
		$this->template->view('viewCar');
	}

	function newCar() {
		$results = array();
		$results['pageTitle'] = "New Car";
		$results['formAction'] = "newCar";

		if ( isset( $_POST['saveChanges'] ) ) {
			// User has posted the car edit form: save the new car
			$car = new Model_Car;
			$car->storeFormValues( $_POST );
			$car->insert();
			print_r($car);
			header( "Location: /mvc/admin/car/listCars?status=changesSaved" );
		} elseif ( isset( $_POST['cancel'] ) ) {
			// User has cancelled their edits: return to the car list
			header( "Location: /mvc/admin/car/listCars" );
		} else {
			// User has not posted the car edit form yet: display the form
			$results['car'] = new Model_Car;
			$data = Model_Category::getList();
			$results['categories'] = $data['results'];
			$this->template->vars('results', $results);
			$this->template->view('editCar', 'admin');
		}
	}


	function editCar() {
		$results = array();
		$results['pageTitle'] = "Edit Car";
		$results['formAction'] = "editCar";

		if ( isset( $_POST['saveChanges'] ) ) {
			// User has posted the car edit form: save the car changes
			if ( !$car = Model_Car::getById( (int)$_POST['carId'] ) ) {
				header( "Location: /mvc/admin/car/listCars?error=carNotFound" );
				return;
			}
			$car->storeFormValues( $_POST );
			$car->update();
			header( "Location: /mvc/admin/car/listCars?status=changesSaved" );

		} elseif ( isset( $_POST['cancel'] ) ) {
			// User has cancelled their edits: return to the car list
    		header( "Location: /mvc/admin/car/listCars" );
		} else {
			// User has not posted the car edit form yet: display the form
			$results['car'] = Model_Car::getById( (int)$_GET['carId'] );
			$data = Model_Category::getList();
			$results['categories'] = $data['results'];
			$this->template->vars('results', $results);
			$this->template->view('editCar', 'admin');
		}
	}


	function deleteCar() {

		if ( !$car = Model_Car::getById( (int)$_GET['carId'] ) ) {
			header( "Location: /mvc/admin/car/listCars?error=carNotFound" );
			return;
		}

		$car->delete();
		header( "Location: /mvc/admin/car/listCars?status=carDeleted" );
	}


	function listCars() {
		$results = array();
		$data = Model_Car::getList();
		$results['cars'] = $data['results'];
		$results['totalRows'] = $data['totalRows'];
		$results['images'] = array();
		foreach ( $results['cars'] as $car ) {
			$temp = Model_Image::getByCarId($car->id);
			if($temp['totalRows'] > 0)
				$results['images'][$car->id] = $temp['results'][0];
		}

		$results['pageTitle'] = "All Cars";

		if ( isset( $_GET['error'] ) ) {
			if ( $_GET['error'] == "carNotFound" ) $results['errorMessage'] = "Error: Car not found.";
		}

		if ( isset( $_GET['status'] ) ) {
			if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
			if ( $_GET['status'] == "carDeleted" ) $results['statusMessage'] = "Car deleted.";
		}

		$this->template->vars('results', $results);
		$this->template->view('listCars', 'admin');
	}
}