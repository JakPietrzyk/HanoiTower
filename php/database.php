<?php
class db
{
  private $dbase;
  private $collection;
  static $dsn = 'sqlite:sql/baza.db';
  protected static $dbs;
  private $sth;


  function __construct()
  {
    $data = explode(':', self::$dsn);
    if (!file_exists($data[1])) {
      throw new Exception("Database file doesn't exist.");
    }
    self::$dbs = new PDO(self::$dsn);
    self::$dbs->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }

  public function checkIfLoginExists($username)
  {
    $this->sth = self::$dbs->prepare('SELECT COUNT(*) as count FROM user WHERE username = :username');
    $this->sth->bindParam(':username', $username);
    $this->sth->execute();
    $result = $this->sth->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
  }

  function addUser($user)
  {
    try {
      if ($this->checkIfLoginExists($user['username']))
        throw new Exception('Login already exists');
      $query = "INSERT INTO user (username, password) VALUES (:username, :password)";
      $stmt = self::$dbs->prepare($query);
      $hashedPassword = md5($user['password']);

      $stmt->bindParam(':username', $user['username']);
      $stmt->bindParam(':password', $hashedPassword);
      $stmt->execute();
      return self::$dbs->lastInsertId();
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  public function validateUser($user)
  {
    $login = $user['username'];
    $hashedPassword = md5($user['password']);

    $this->sth = self::$dbs->prepare('SELECT * FROM user WHERE username = :username AND password = :password');
    $this->sth->bindParam(':username', $login);
    $this->sth->bindParam(':password', $hashedPassword);
    $this->sth->execute();
    $user = $this->sth->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      session_start();
      $_SESSION['user_login'] = $login;
      setcookie('user_login', $login, time() + (86400 * 30), "/");
      return true;
    }

    return false;
  }

  function getUser($user)
  {
    try {

      $query = "INSERT INTO user (username, password) VALUES (:username, :password)";
      $stmt = self::$dbs->prepare($query);
      $stmt->bindParam(':username', $user['username']);
      $stmt->bindParam(':password', $user['password']);
      $stmt->execute();
      return self::$dbs->lastInsertId();
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }

  function getUserIdByLogin($login)
  {
    $sth = self::$dbs->prepare('SELECT user_id FROM User WHERE username = :username');
    $sth->bindParam(':username', $login);
    $sth->execute();
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    return $result['user_id'];
  }


  function addPreferences($preferences)
  {
    try {
      if (isset($_COOKIE['user_login'])) {
        $login = $_COOKIE['user_login'];
        $user_id = $this->getUserIdByLogin($login);
      } else {
        throw new Exception('User not logged in');
      }

      $query = "UPDATE UserPreferences SET numberOfDisks = :numberOfDisks, animationSpeed = :animationSpeed WHERE user_id = :user_id";
      $stmt = self::$dbs->prepare($query);
      $stmt->bindParam(':numberOfDisks', $preferences['numberOfDisks']);
      $stmt->bindParam(':animationSpeed', $preferences['animationSpeed']);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();

      return true;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }

  }
  function getPreferences()
  {
    try {
      if (isset($_COOKIE['user_login'])) {
        $login = $_COOKIE['user_login'];
        $user_id = $this->getUserIdByLogin($login);
      } else {
        throw new Exception('User not logged in');
      }

      $query = "SELECT * FROM UserPreferences WHERE user_id = :user_id";
      $stmt = self::$dbs->prepare($query);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
      return false;
    }
  }
}