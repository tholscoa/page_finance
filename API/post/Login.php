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

  $post->email = $data->email;
  $post->password = $data->password;

  // user Login
  $user = $post->Login($post->email, $post->password);
  if($user[0]) {
    echo json_encode(
      array('message' => 'Login successful', 'api-key'=>$user[1])
    );
  } else {
    echo json_encode(
      array('message' => 'Unauthorized')
    );
  }

