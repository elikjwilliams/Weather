<?php

$username = htmlentities($_REQUEST["username"]);
$oldpassword = htmlentities($_REQUEST["oldpassword"]);
$newpassword = htmlentities($_REQUEST["newpassword"]);
    
    if(empty($username) || empty($oldpassword) || empty($newpassword)){
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
    $user = $access->login($oldpassword, $spw);

    //valid username and password    
    if($user){
    	//secure password
    $securedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
    $result = $access->updatePassword($username, $securedPassword);

         $returnArray["status"] = "200";
         $returnArray["message"] = "Sucessfully Changed Password";
    }

    //invalid username and password
    else{
        $returnArray["status"] = "400";
        $returnArray["message"] = "Invalid password";
    }

        //close connection
    $access->disconnect();

    echo json_encode($returnArray);

    ?>
