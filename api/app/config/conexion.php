<?php
$dotenv = Dotenv\Dotenv::createImmutable('../../');
$dotenv->load();
class DatabaseService
{
  public function getConnection()
  {
    $db_host = $_ENV['db_host'];;
    $db_name = $_ENV['db_name'];
    $db_user = $_ENV['db_user'];
    $db_password = $_ENV['db_password'];
    /*$db_host = 'localhost';
    $db_name = 'bd_colegio2';
    $db_user = 'root';
    $db_password = '';*/

    try {
      Flight::register('db', \flight\database\PdoWrapper::class, [
        'mysql:host=' . $db_host . ";dbname=" . $db_name,
        $db_user,
        $db_password,
        [
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8mb4\'',
          PDO::ATTR_EMULATE_PREPARES => false,
          PDO::ATTR_STRINGIFY_FETCHES => false,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      ]);

      $db = Flight::db();
    } catch (PDOException $exception) {
      echo "Connection failed: " . $exception->getMessage();
    }

    return $db;
  }
}
