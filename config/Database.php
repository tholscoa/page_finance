<?php 

  class Database {
    // DB Parameter settings
    private $host = 'localhost';
    private $db_name = 'pagefi';
    private $username = 'takinnuoye';
    private $password = 'Nigeria@123';
    private $conn;

    // DB Connect method
    public function connect() {
      $this->conn = null;
//creating a POD OBJECT
      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }


      return $this->conn;
    }
  }