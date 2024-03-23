<?php
class Employee{
    public function get_data($eid){
        
        $query = "SELECT * FROM employee WHERE employeeid = '$eid' limit 1";
        
        $DB = new Database();
        $result = $DB->read($query);
        
        if($result){
            $row = $result[0];
            return $row;
        } else {
            return false;
        }
    }
}
?>