<?php
  require_once 'base.php';

  //create connection. First need to instantiate new mysqli class
  $conn =  new Mysqli(MY_SERVER, MY_USERNAME, MY_PASSWORD, MY_DBNAME);

  //check if connection is successful
  if ($conn->connect_error){
      die("Connection failed:". $conn->connect_error);
  }
  //echo "Connected successfully";
?>