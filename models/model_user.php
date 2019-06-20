<?php

/**
 * Класс для обработки пользователей
 */

class Model_User
{
  // Свойства

  /**
  * @var int ID пользователя из базы данных
  */
  public $id = null;

  /**
  * @var string Имя пользователя
  */
  public $name = null;

  /**
  * @var string Почта пользователя
  */
  public $email = null;

  /**
  * @var string Пароль пользователя
  */
  public $password = null;

  /**
  * @var string IP пользователя
  */
  public $IP = null;

  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['name'] );
    if ( isset( $data['email'] ) ) $this->email = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['email'] );
    if ( isset( $data['password'] ) ) $this->password = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['password'] );
    if ( isset( $data['IP'] ) ) $this->IP = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['IP'] );
  }


  /**
  * Устанавливаем свойства с помощью значений формы редактирования записи в заданном массиве
  *
  * @param assoc Значения записи формы
  */

  public function storeFormValues ( $params ) {
    // Сохраняем все параметры
    $this->__construct( $params );
  }

  /**
  * Возвращает все объекты пользователей в базе данных
  *
  */
  public static function getList( $numRows=1000000, $order="name" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users ORDER BY " . $order;
    $st = $conn->prepare( $sql );
    $st->execute();
    $list = array();

    $totalRows = 0;
    while ( $row = $st->fetch() ) {
      $user = new Model_User( $row );
      $list[] = $user;
      $totalRows++;
    }
    return ( array ( "results" => $list, "totalRows" => $totalRows ) );
  }

  /**
  * Возвращаем объект пользователя соответствующий заданному ID пользователя
  *
  * @param int ID пользователя
  * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Model_User( $row );
  }

  /**
  * Возвращаем объект пользователя соответствующий заданному name пользователя
  *
  * @param int ID пользователя
  * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
  */

  public static function getByName( $name ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users WHERE name = :name";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":name", $name, PDO::PARAM_STR );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Model_User( $row );
  }

  /**
  * Возвращаем объект пользователя соответствующий заданному email пользователя
  *
  * @param int ID пользователя
  * @return User|false Объект пользователя или false, если запись не найдена или возникли проблемы
  */

  public static function getByEmail( $email ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM users WHERE email = :email";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":email", $email, PDO::PARAM_STR );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Model_User( $row );
  }

  /**
  * Вставляем текущий объект пользователя в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Вставляем пользователя
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO users ( name, email, password, IP ) VALUES ( :name, :email, :password, :IP )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":email", $this->email, PDO::PARAM_STR );
    $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
    $this->IP = $_SERVER['REMOTE_ADDR'];
    $st->bindValue( ":IP", $this->IP, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Обновляем текущий объект пользователя в базе данных
  */
  public function update() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "User::update(): Attempt to update an User object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем пользователя
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE users SET name=:name, email=:email, password=:password, IP=:IP WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":email", $this->email, PDO::PARAM_STR );
    $st->bindValue( ":password", $this->password, PDO::PARAM_STR );
    $this->IP = $_SERVER['REMOTE_ADDR'];
    $st->bindValue( ":IP", $this->IP, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Удаляем текущий объект пользователя из базы данных
  */

  public function delete() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "User::delete(): Attempt to delete an User object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем пользователя
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM users WHERE id = :id" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  public function startSession() {
    // Вход прошел успешно: создаем сессию
    session_start();
    $_SESSION['user'] = $this->name;
  }

  public function endSession() {
    unset($_SESSION['user']);
  }

}

?>