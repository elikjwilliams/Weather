<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $username = htmlentities($_REQUEST["username"]);
    $password = htmlentities($_REQUEST["password"]);
    $email = htmlentities($_REQUEST["email"]);
    $fullname = htmlentities($_REQUEST["fullname"]);
    
    if(empty($username) || empty($password) || empty($email) || empty($fullname)){
        $returnArray = [
        "status" => "400",
        "message" => "Missing required information",
        ];
        echo json_encode($returnArray);
        return;
    }
    
    //secure password
    $securedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    //secure way to build connection
    $file = parse_ini_file("../../../PlayAround.ini");
    
    //store info from ini variables
    $host = trim($file["dbhost"]);
    $user = trim($file["dbuser"]);
    $pw = trim($file["dbpassword"]);
    $name = trim($file["dbname"]);
    
    //include access.php to be able to call functions
    require "secure/acess.php";
    $access = new access($host, $user, $pw, $name);
    $access->connect();
    
    //insert user information into the db
        $result = $access->registerUser($username, $securedPassword, $email, $fullname);
        if($result){

            $user = $access->selectUser($username);
            
            //shows the db row
            $returnArray["status"] = "200";
            $returnArray["message"] = "Sucessfully registered";
            $returnArray["id"] = $user["id"];
            $returnArray["username"] = $user["username"];
            $returnArray["email"] = $user["email"];
            $returnArray["fullname"] = $user["fullname"];
            
        }
        
        //could not register user
        else{
            $returnArray["status"] = "400";
            $returnArray["message"] = "Could not regiseter user with the required information";
        }
    
    //close connection
    $access->disconnect();
    
    //show json data
    echo json_encode($returnArray);

?>
