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

  $post->email = $data->email;
  $getuser = $post->getUser();
  if(!$getuser[0]){
    return print_r(json_encode(['message'=>$getuser[1]]));
  }
  $user = $getuser[1];
    // Get User Wallets
    $wallets = $post->getUserWallets($user['id']);
    $transactions = $post->getUserTransactions($user['id']);
    // return print_r($wallets);
    // $user_Walletdetails=array('id' => $user->id,'email' => $user->email,'name' => $user->name);
  
  
    $post_arr = array(
      
      'id' => $user['id'],
      'email' => $user['email'],
      'name' => $user['name'],
      'wallets'=>$wallets,
      'transaction_history'=>$transactions
    );
  
    // Make JSON or convert tom JSON 
    print_r(json_encode($post_arr));

  