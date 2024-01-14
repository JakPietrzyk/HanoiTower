<?php
      ini_set('display_errors', '1');
      ini_set('display_startup_errors', '1');
      error_reporting(E_ALL);
//  use PDO;

 class db {
    private $dbase;
    private $collection;
    static $dsn = 'sqlite:sql/baza.db'  ;
    protected static $dbs ;
    private $sth ;
  
  
     function __construct() {
       $data = explode(':',self::$dsn) ;
       if ( ! file_exists ( $data[1] ) ) { throw new Exception ( "Database file doesn't exist." ) ;  }
       self::$dbs = new PDO ( self::$dsn ) ;
       self::$dbs->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ; 
     }

     public function checkIfLoginExists($username)
     {
       $this->sth = self::$dbs->prepare('SELECT COUNT(*) as count FROM user WHERE username = :username');
       $this->sth->bindParam(':username', $username);
       $this->sth->execute();
       $result = $this->sth->fetch(PDO::FETCH_ASSOC);
       return $result['count'] > 0;
     }
  
   function addUser($user) {
    try {
        if($this->checkIfLoginExists($user['username']))
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
        // $_SESSION['user_id'] = $user['id'];
        setcookie('user_login', $login, time() + (86400 * 30), '/');
        return true;
     }

     return false;
  }

  function getUser($user) {
    try {

        $query = "INSERT INTO user (username, password) VALUES (:username, :password)";
        $stmt = self::$dbs->prepare($query);
        $stmt->bindParam(':username', $user['username']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->execute();
        return self::$dbs->lastInsertId();
    } catch (Exception $e) {
        // Log or handle the exception as needed
        echo "Error: " . $e->getMessage();
        return false;
    }
  }


  
 }