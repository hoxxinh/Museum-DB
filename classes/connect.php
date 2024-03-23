<?php

class Database {
    
    private $host = "museum.cpm4eq2ycfx2.us-east-1.rds.amazonaws.com";
    private $username = "admin";
    private $password = "museumteam5";
    private $db = "MfahDB";
    
    
    function connect(){
        // Attempt to connect to MySQL
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        
        if ($connection->connect_error) {
          echo "Connecting failed";
          die("Connection failed: " . $connection->connect_error);
        }
        //echo "Connected successfully";
        return $connection;
    }
    
    function read($query){
        // Collect data
        $data = array(); // Initialize as an empty array
        $conn = $this->connect();
        $result = mysqli_query($conn,$query);
        if(!$result){
            return false;
        } else {
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            return $data;
        }
    }


    function save($sql){
        $conn = $this->connect();
        if (mysqli_query($conn, $sql)) {
          //echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

?>
