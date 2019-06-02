<?php
  require_once 'base.php';

  //create connection. First need to instantiate new mysqli class
  $conn =  new Mysqli('localhost', 'root', '', 'hotels');

  //check if connection is successful
  if ($conn->connect_error){
      die("Connection failed:". $conn->connect_error);
  }
  //echo "Connected successfully";
?>