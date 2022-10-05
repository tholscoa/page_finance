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

  $post->wallet_id = $data->wallet_id;
  $getwallet = $post->getWallet();
  if(!$getwallet[0]){
    return print_r(json_encode(['message'=>$getwallet[1]]));
  }
  $wallet = $getwallet[1];
    // Get User Wallets
    $transactions = $post->getWalletTransactions($wallet['wallet_id']);

  
    $post_arr = array(
      "id"=> $wallet["id"],
      "owner"=> $wallet["owner"],
      "owner_email"=> $wallet["owner_email"],
      "wallet_id"=> $wallet["wallet_id"],
      "balance"=> $wallet["balance"],
      "status"=> $wallet["status"],
      "wallet_type"=> $wallet["wallet_type"],
      "minimum_balance"=> $wallet["minimum_balance"],
      'transaction_history'=>$transactions
    );
  
    // Make JSON or convert tom JSON 
    print_r(json_encode($post_arr));

  