<?php 
#This file create wallet for user
#
#
#
#
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../Models/Services.php';

  $header_passed = isset($_SERVER['HTTP_API_KEY']) ? $_SERVER['HTTP_API_KEY'] : false;
  if(!$header_passed){
    print_r(json_encode(['message' =>'API KEY not passed. Unauthorized.']));
    return;
  }

  
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();
  
  // Instantiate blog post object
  $post = new Services($db);
  
  //check authentication
  $authenticate = $post->authenticate($header_passed);

  if(!$authenticate[0]){
    print_r(json_encode(['message' => $authenticate[1]]));
    return; 
  }

  $user = $authenticate[1];

  $data = json_decode(file_get_contents("php://input"));

  $post->wallet_type_id = $data->wallet_type_id;
  return print_r($post->CreateUserWallet($user['id']));
  if($post->CreateUserWallet($user['id'])) {
    echo json_encode(
      array('message' => 'User wallet  Created Successfully')
    );
  } else {
    echo json_encode(
      array('message' => 'Error occured while creating user wallet')
    );
  }