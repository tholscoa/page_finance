<?php 
#This file read a single user details and all wallet attached to the user  from the application
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

  $data = json_decode(file_get_contents("php://input"));

  $process_transfer = $post->transfer($data->source_wallet_id, $data->beneficiary_wallet_id, $data->amount, $data->narrative);
  if(!$process_transfer[0]){
    return print_r(json_encode(['message'=>$process_transfer[1]]));
  }
  $transfer = $process_transfer[1];
   
  return print_r(json_encode($transfer));

  