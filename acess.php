<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//class to access php file
class access {
    
    //global connection variables
    var $host = null;
    var $user = null;
    var $password = null;
    var $name = null;
    var $conn = null;
    var $result = null;
    
    //constructing function
    function __construct($dbhost, $dbuser, $dbpassword, $dbname) {
        $this->host = $dbhost;
        $this->user = $dbuser;
        $this->password = $dbpassword;
        $this->name = $dbname;

        
    }
    
    //connection function
    public function connect(){
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->name);
        
        if(mysqli_connect_errno()){
            echo 'Could not connect to database';
        }
        
        $this->conn->set_charset("utf8");
    }
    
    //disconnection function
    public function disconnect(){
       if($this->conn != null) {
           $this->conn->close();
       }
    }
    
    //insert user details
    public function registerUser($username, $password, $email, $fullname){
            
            //sql command
            $sql = "INSERT INTO users SET username=?, password=?, email=?, fullname=?";
            
            //store query in statement
            $statement = $this->conn->prepare($sql);
            
            //if error
            if(!$statement){
                throw new Exception($statement->error);
            }
            
            //bind 4 params of type string to sql query
            $statement->bind_param('ssss', $username, $password, $email, $fullname);
            
            //excute sql query
            $result = $statement->execute();
            
            //show result of the sql query
            return $result;

        
    }
    
    //select user info
    public function selectUser($username){
        
        
        //sql command
        $sql = "SELECT * FROM users WHERE username='".$username."'";
        
        //assign result from sql query to result
        $result = $this->conn->query($sql);
        
        //if one result row returned
        if($result != "null" && mysqli_num_rows($result) >= 1){
            
            
            //assign results to rown as array
            $row = $result->fetch_array(MYSQLI_ASSOC);
            
            if(!empty($row)){
                $returnArray = $row;
            }
            else{
                $returnArray = false;
            }
        }
        
        return $returnArray;
    }
    
    //compare users inputted password and db password to see if they are a valid user
    public function login($password, $spw){

            //check if the passwords match
            if(password_verify($password, $spw)){
                return true;
            }

            else{
                return false;
            }
        
    }

    //change password
    public function updatePassword($username, $password){

         //sql command
        $sql = "UPDATE users SET password=? WHERE username=?";

        //store query in statement
        $statement = $this->conn->prepare($sql);
            
            //if error
            if(!$statement){
                throw new Exception($statement->error);
            }
            
            //bind 5 params of type string to sql query
            $statement->bind_param('ss', $password, $username);
            
            //excute sql query
            $result = $statement->execute();

/*
          if($rowsaffected != 1){
            return true;
          }
          else {
            return false;
          }
          */
    }
}

?>
