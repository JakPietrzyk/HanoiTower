<?php
      ini_set('display_errors', '1');
      ini_set('display_startup_errors', '1');
      error_reporting(E_ALL);
 use PDO;

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
  
  
   function addUser($user) {
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



  public function checkIfLoginExists($login)
  {
    $this->sth = self::$db->prepare('SELECT COUNT(*) as count FROM user WHERE login = :login');
    $this->sth->bindParam(':login', $login);
    $this->sth->execute();
    $result = $this->sth->fetch(PDO::FETCH_ASSOC);
    return $result['count'] > 0;
  }
  
 }