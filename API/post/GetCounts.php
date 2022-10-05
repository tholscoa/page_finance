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
  $users = $post->GetAllUsers();
  $user_count = (count($users->fetchAll(PDO::FETCH_ASSOC)));

  $wallet_query = $post->GetAllWallets();
  $wallets_count = (count($wallet_query->fetchAll(PDO::FETCH_ASSOC)));

  $total_wallet_balance_query = $post->GetTotalWalletsBalance();
  $total_wallet_balance = (($total_wallet_balance_query->fetch(PDO::FETCH_ASSOC)));

  $total_transactions_volume_query = $post->GetTotalTransactionVolume();
  $total_transactions_volume = (count($total_transactions_volume_query->fetchAll(PDO::FETCH_ASSOC)));

  $array = [];
  $array['user_count'] = $user_count;
  $array['wallets_count'] = $wallets_count;
  $array['total_wallet_balance'] = $total_wallet_balance["SUM(balance)"];
  $array['total_transactions_volume'] = $total_transactions_volume;

  return print_r(json_encode($array));