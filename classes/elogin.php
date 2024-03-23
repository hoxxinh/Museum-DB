<?php
class Elogin{
    private $error = "";
   
    public function evaluate($data){
        
        $username = addslashes($data['username']);
        $password = addslashes($data['password']);
        
        $sql = "SELECT * FROM employee WHERE username = '$username' limit 1";       
        
        $DB = new Database();
        $result = $DB->read($sql);
        
        if($result){
            $row = $result[0];
            if($password == $row['Password']){
                
                //create a session data
                $_SESSION['museum_employeeid'] = $row['EmployeeID'];
                
            } else {
                $this->error .= "Invalid Username/Password<br>";
            }
        } else {
            $this->error .= "Invalid Username/Password<br>";
        }
            
        return $this->error;
    }
    
    public function check_elogin($eid){
        $equery = "SELECT employeeid FROM employee WHERE employeeid = '$eid' limit 1";       
        
        $DB = new Database();
        $result = $DB->read($equery);
        
        if($result){       
            return true;
        }   
        return false;
    }
}

?>