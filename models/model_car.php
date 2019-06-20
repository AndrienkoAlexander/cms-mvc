<?php

/**
 * Класс для обработки товаров(машин)
 */

class Model_Car
{
  // Свойства

  /**
  * @var int ID машин из базы данных
  */
  public $id = null;

  /**
  * @var string Название машины
  */
  public $name = null;

  /**
  * @var int Цена машины
  */
  public $price = null;

  /**
  * @var int Серийный номер
  */
  public $serial_num = null;

  /**
  * @var int Год выпуска
  */
  public $year = null;

  /**
  * @var string Коробка передач
  */
  public $gear_box = null;

    /**
  * @var int Мощность
  */
  public $power = null;

    /**
  * @var int Родитель(Категория)
  */
  public $categoryId = null;

  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['name'] );
    if ( isset( $data['price'] ) ) $this->price = (int) $data['price'];
    if ( isset( $data['serial_num'] ) ) $this->serial_num = (int) $data['serial_num'];
    if ( isset( $data['year'] ) ) $this->year = (int) $data['year'];
    if ( isset( $data['gear_box'] ) ) $this->gear_box = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['gear_box'] );
    if ( isset( $data['power'] ) ) $this->power = (int) $data['power'];
    if ( isset( $data['categoryId'] ) ) $this->categoryId = (int) $data['categoryId'];
  }


  /**
  * Устанавливаем свойств с помощью значений формы редактирования записи в заданном массиве
  *
  * @param assoc Значения записи формы
  */

  public function storeFormValues ( $params ) {
    // Сохраняем все параметры
    $this->__construct( $params );
  }

  /**
  * Возвращает все (или диапазон) объектов машин в базе данных
  *
  */
  public static function getList( $numRows=1000000, $categoryId=null, $order="name" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
    $sql = "SELECT * FROM cars " . $categoryClause . " ORDER BY " . $order;
    $st = $conn->prepare( $sql );
    if ( $categoryId ) $st->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    $totalRows = 0;
    while ( $row = $st->fetch() ) {
      $car = new Model_Car( $row );
      $list[] = $car;
      $totalRows++;
    }
    return ( array ( "results" => $list, "totalRows" => $totalRows ) );
  }

  /**
  * Возвращаем объект машины соответствующий заданному ID машины
  *
  * @param int ID машины
  * @return Car|false Объект машины или false, если запись не найдена или возникли проблемы
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM cars WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Model_Car( $row );
  }


  /**
  * Вставляем текущий объект машины в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Есть у объекта статьи ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Car::insert(): Attempt to insert an Car object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Вставляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO cars (name, price, serial_num, year, gear_box, power, categoryId ) VALUES (:name, :price, :serial_num, :year, :gear_box, :power, :categoryId)";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":price", $this->price, PDO::PARAM_INT );
    $st->bindValue( ":serial_num", $this->serial_num, PDO::PARAM_INT );
    $st->bindValue( ":year", $this->year, PDO::PARAM_INT );
    $st->bindValue( ":gear_box", $this->gear_box, PDO::PARAM_STR );
    $st->bindValue( ":power", $this->power, PDO::PARAM_INT );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Обновляем текущий объект машины в базе данных
  */
  public function update() {

    // Есть ли у объекта статьи ID?
    if ( is_null( $this->id ) ) trigger_error ( "Car::update(): Attempt to update an Car object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE cars SET name=:name, price=:price, serial_num=:serial_num, year=:year, gear_box=:gear_box, power=:power, categoryId=:categoryId WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":price", $this->price, PDO::PARAM_INT );
    $st->bindValue( ":serial_num", $this->serial_num, PDO::PARAM_INT );
    $st->bindValue( ":year", $this->year, PDO::PARAM_INT );
    $st->bindValue( ":gear_box", $this->gear_box, PDO::PARAM_STR );
    $st->bindValue( ":power", $this->power, PDO::PARAM_INT );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Удаляем текущий объект машины из базы данных
  */

  public function delete() {

    // Есть ли у объекта статьи ID?
    if ( is_null( $this->id ) ) trigger_error ( "Car::delete(): Attempt to delete an Car object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM cars WHERE id = :id" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>