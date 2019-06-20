<?php

/**
 * Класс для обработки комментариев
 */

class Model_Comment
{
  // Свойства

  /**
  * @var int ID комментария из базы данных
  */
  public $id = null;

  /**
  * @var string Время публикации комментария
  */
  public $date_time = null;

  /**
  * @var string Текст комментария
  */
  public $message = null;

  /**
  * @var int Родитель комментария(к какой записи относится)
  */
  public $parent = null;

  /**
  * @var int ID пользователя, автор комментария
  */
  public $user_id = null;

  /**
  * @var bool Показывать комментарий?
  */
  public $shown = true;

  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['date_time'] ) ) $this->date_time = date("d.m.Y H:i", strtotime($data['date_time']));
    if ( isset( $data['message'] ) ) $this->message = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['message'] );
    if ( isset( $data['parent'] ) ) $this->parent = (int) $data['parent'];
    if ( isset( $data['user_id'] ) ) $this->user_id = (int) $data['user_id'];
    if ( isset( $data['shown'] ) ) $this->shown = (bool) $data['shown'];
  }

  /**
  * Инвертирует значение shown
  */

  public function invertShown() {
    $this->shown = $this->shown == true ? false : true;
    $this->update();
  }

  /**
  * Возвращает все объекты комментария в базе данных
  *
  */
  public static function getList( $numRows=1000000, $order="date_time DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM comments ORDER BY " . $order;
    $st = $conn->prepare( $sql );
    $st->execute();
    $list = array();

    $totalRows = 0;
    while ( $row = $st->fetch() ) {
      $comment = new Model_Comment( $row );
      $list[] = $comment;
      $totalRows++;
    }
    return ( array ( "results" => $list, "totalRows" => $totalRows ) );
  }

  /**
  * Возвращает все объекты комментария в базе данных
  *
  */
  public static function getListByCarID( $parent, $numRows=1000000, $order="date_time DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM comments WHERE parent = :parent ORDER BY " . $order;
    $st = $conn->prepare( $sql );
    $st->bindValue( ":parent", $parent, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    $totalRows = 0;
    while ( $row = $st->fetch() ) {
      $comment = new Model_Comment( $row );
      $list[] = $comment;
      $totalRows++;
    }
    return ( array ( "results" => $list, "totalRows" => $totalRows ) );
  }

  /**
  * Возвращаем объект комментария соответствующий заданному ID комментария
  *
  * @param int ID комментария
  * @return Comment|false Объект комментария или false, если запись не найдена или возникли проблемы
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM comments WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Model_Comment( $row );
  }


  /**
  * Вставляем текущий объект комментария в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Есть у объекта ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Comment::insert(): Attempt to insert an Comment object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Вставляем комментария
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO comments ( date_time, message, parent, user_id, shown ) VALUES ( now(), :message, :parent, :user_id, :shown )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":message", $this->message, PDO::PARAM_STR );
    $st->bindValue( ":parent", $this->parent, PDO::PARAM_INT );
    $st->bindValue( ":user_id", $this->user_id, PDO::PARAM_INT );
    $st->bindValue( ":shown", $this->shown, PDO::PARAM_BOOL );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }

  /**
  * Удаляем текущий объект комментария из базы данных
  */

  public function delete() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "Comment::delete(): Attempt to delete an Comment object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем комментарий
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM comments WHERE id = :id" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  /**
  * Удаляем объект комментария из базы данных по пользователю
  */

  public function deleteByUser($user_id) {
    // Удаляем комментарий
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM comments WHERE user_id = :user_id" );
    $st->bindValue( ":user_id", $user_id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

  /**
  * Обновляем текущий объект комментария в базе данных
  */
  public function update() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "Comment::update(): Attempt to update an Comment object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем комментарий
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE comments SET shown=:shown WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":shown", $this->shown, PDO::PARAM_BOOL );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}