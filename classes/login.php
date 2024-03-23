<?php
class Login{
    private $error = "";
   
    public function evaluate($data){
        
        $email = addslashes($data['email']);
        $password = addslashes($data['password']);
        
        $sql = "SELECT * FROM users WHERE email = '$email' limit 1";       
        
        $DB = new Database();
        $result = $DB->read($sql);
        
        if($result){
            $row = $result[0];
            
            if($password == $row['password']){
                
                //create a session data
                $_SESSION['museum_userid'] = $row['userid'];
                
            } else {
                $this->error .= "Invalid Password<br>";
            }
        } else {
            $this->error .= "Invalid Email<br>";
        }
            
        return $this->error;
    }
    
    public function check_login($id){
        $query = "SELECT userid FROM users WHERE userid = '$id' limit 1";       
        
        $DB = new Database();
        $result = $DB->read($query);
        
        if($result){
            
            return true;
        }
        
        return false;
        
    }
}

?>