<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $username = htmlentities($_REQUEST["username"]);
    $password = htmlentities($_REQUEST["password"]);
    
    if(empty($username) || empty($password)){
        $returnArray = [
        "status" => "400",
        "message" => "Missing required information",
        ];
        echo json_encode($returnArray);
        return;
    }
    
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

    //get user data from database
    $user = $access->selectUser($username);

    //access db password
    $spw = $user["password"];

    //send username and password to login function
    $user = $access->login($password, $spw);
        
    //valid username and password    
    if($user){
         $returnArray["status"] = "200";
        $returnArray["message"] = "Sucessfully logged In";
        $returnArray["username"] = $username;
    }

    //invalid username and password
    else{
        $returnArray["status"] = "400";
        $returnArray["message"] = "Could not log In with provided information";
    }

    
    //close connection
    $access->disconnect();
    
    //show json data
    echo json_encode($returnArray);

?>
