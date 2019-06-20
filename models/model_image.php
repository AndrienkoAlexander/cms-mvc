<?php

class Model_Image
{
  // Properties

  /**
  * @var int The image ID from the database
  */
  public $id = null;

  /**
  * @var string Name of the image
  */
  public $name = null;

  /**
  * @var int Name of the image
  */
  public $car_id = null;

  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['name'] );
    if ( isset( $data['car_id'] ) ) $this->id = (int) $data['car_id'];
  }


  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */

  public function storeFormValues ( $params ) {
    // Store all the parameters
    $this->__construct( $params );
  }

  public static function getByCarId( $car_id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM images WHERE car_id = :car_id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":car_id", $car_id, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    $totalRows = 0;
    while ( $row = $st->fetch() ) {
      $image = new Model_Image( $row );
      $list[] = $image;
      $totalRows++;
    }

    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows ) );
  }

  public function insert() {

    // Does the Image object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Image::insert(): Attempt to insert a Image object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Insert the Image
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO images ( name, car_id ) VALUES ( :name, :car_id)";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":car_id", $this->name, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Updates the current Image object in the database.
  */
  public function update() {

    // Does the Image object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Image::update(): Attempt to update a Image object that does not have its ID property set.", E_USER_ERROR );
   
    // Update the Image
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE images SET name=:name, car_id=:car_id WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":car_id", $this->car_id, PDO::PARAM_INT );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Deletes the current Image object from the database.
  */

  public function delete() {

    // Does the Image object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Image::delete(): Attempt to delete a Image object that does not have its ID property set.", E_USER_ERROR );

    // Delete the Image
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM images WHERE id = :id" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>