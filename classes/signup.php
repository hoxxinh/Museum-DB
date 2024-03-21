<?php
    //require 'C:\wamp64\www\museum\vendor\autoload.php';
class Signup
{   
    private $error = "";
    private $passwordcheck = "";
    private $emailcheck = "";
    
    public function evaluate($data){
        foreach ($data as $key => $value){
            if(empty($value)){
                $this->error = $this->error . $key . " is empty!<br>";
            }
            if($key == "password"){
                $this->passwordcheck = $value;
            }
            if($key == "re-password"){
                if ($this->passwordcheck != $value) {
                    $this->error = $this->error . "Password doesn't match<br>";
                }
            }
            if($key == "email" && $value != ""){
                if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $value)){
                    $this->error = $this->error . "Invalid Email!<br>";
                } else {
                    // Check if email already exists
                    $existingEmail = $this->checkExistingEmail($value);
                    if ($existingEmail) {
                        $this->error = $this->error . "Email already exists!<br>";
                    }
                }    
            }
            if($key == "first_name"){
                if (is_numeric($value)){
                    $this->error = $this->error . "Invalid First Name!<br>";
                }     
            }
            if($key == "last_name"){
                 if (is_numeric($value)){
                    $this->error = $this->error . "Invalid Last Name!<br>";
                }     
            }
        }
        if($this->error == ""){
            echo '<script>
                      alert("User created successfully!");
                      window.location.href = "login.php"; // Redirect to login page
                  </script>'; // Display pop-up message and redirect
            $this->create_user($data);
        } else {
            echo '<script>
                      alert("' . str_replace("<br>", '\\n', $this->error) . '");
                  </script>';
            return;
        }
    }
    
    private function checkExistingEmail($email) {
        $DB = new Database();
        $query2 = "SELECT COUNT(*) AS count FROM users WHERE email = '$email'";
        $result = $DB->read($query2);
        $count = $result[0]['count'];
        
        if ($count == 1) {     
            return true;
        } else {
            return false;
        }
    }
    

    public function create_user($data){
        
        $email = $data['email'];
        $password = $data['password'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        
        //$userid = $this->create_userid();
        $url_address = strtolower($first_name) . "." . strtolower($last_name);
        $sql = "INSERT INTO users (first_name,last_name,email,password,url_address) 
        VALUES ('$first_name','$last_name','$email','$password','$url_address')";       
        
        $DB = new Database();
        $DB->save($sql);
    }
    
    //private function create_userid(){
        //$length = rand(4,19);
        //$number = "";
        //for($i=0; $i < $length ;$i++){
        //    $new_rand = rand (0,9);
        //    
        //    $number = $number . $new_rand;
        //}
        //return $number;
    //}
}