<?php 
#This file read all users details from the application
#
#
#
#

  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../Models/Services.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $post = new Services($db);

  // Blog post query
  $result = $post->GetAllWallets();
  

  $row = $result->fetchAll(PDO::FETCH_ASSOC);

  // return print_r(count($row));
  if(count($row) > 0){
    // Turn to JSON & output
    echo json_encode($row);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No Wallet Found')
    );
  }