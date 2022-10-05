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

  