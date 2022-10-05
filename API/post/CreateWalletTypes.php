<?php 

#This file create a user for on the application
#
#
#
#


  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../Models/Services.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate  post object
  $post = new Services($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  $post->name = $data->name;
  $post->minimum_balance = $data->minimum_balance;
  $post->interest_rate = $data->interest_rate;




  // Create User posting to userdetails DB
  if($post->CreateWalletTypes()) {
    echo json_encode(
      array('message' => 'Wallet Type Created Successfully')
    );
  } else {
    echo json_encode(
      array('message' => 'Error occured while creating wallet type')
    );
  }

