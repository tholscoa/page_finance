<?php
class Services
{
  // DB stuff
  private $conn; //conncetion string
  private $walletdetails = 'walletdetails'; //wallet Table
  private $users = 'users'; // User Table

  private $wallet_types = 'wallet_types';


  // Constructor with DB
  //method that run automatically when a class is called/instantiate and pass the database object
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Get Single User Wallet Details
  public function getUserWallets($userId)
  {
    // Create query
    $query = "SELECT a.wallet_id, a.balance, a.status, b.name as wallet_type, b.minimum_balance FROM user_wallets a
    join wallet_types b on a.wallet_type = b.id
    where a.user_id = ? ";


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $userId);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [];
    } else {
      // Set properties
      return $row;
    }
  }

  // Get Single User Transactions history
  public function getUserTransactions($userId)
  {
    // Create query
    $query = "SELECT t.transaction_id, t.reference_no, t.amount, t.transaction_type, t.source_wallet_id, t.beneficiary_wallet_id, t.status, t.created_at, w.wallet_id  FROM transactions t
    join user_wallets w on w.wallet_id = t.source_wallet_id or w.wallet_id = t.beneficiary_wallet_id where w.user_id = ? ";


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $userId);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [];
    } else {
      // Set properties
      return $row;
    }
  }


  /**Create a user  */

  public function CreateUser()
  {
    // Create User query
    $query = 'INSERT INTO ' . $this->users . ' SET name = :name, email = :email, password = :password';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));


    // Bind data
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':password', $this->password);


    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }

  /*************Create a user end *********************/


  /*************CreateWalletTypes*********************/
  public function CreateWalletTypes()
  {
    // Create User query
    $query = 'INSERT INTO ' . $this->wallet_types . ' SET name =:name, minimum_balance =:minimum_balance, interest_rate=:interest_rate';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->minimum_balance = htmlspecialchars(strip_tags($this->minimum_balance));
    $this->interest_rate = htmlspecialchars(strip_tags($this->interest_rate));

    // Bind data
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':minimum_balance', $this->minimum_balance);
    $stmt->bindParam(':interest_rate', $this->interest_rate);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: %s.\n", $stmt->error);

    return false;
  }


  /************* *********************/


  // Login user
  public function Login()
  {
    // Create query
    $query = 'SELECT * FROM users WHERE email = ? and password = ?';


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->email);
    $stmt->bindParam(2, $this->password);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [false, 'Failed'];
    }

    return [true, base64_encode($this->email . ":" . $this->password)];
  }


   // Get All 
  public function GetWalletTypes()
  {
    // Create query
    //   $query = 'SELECT Id,UserId,WalletId,WalletName,CreatedDate,MinBalance,MonthlyRate FROM walletdetails';

    $query = 'SELECT id, name, minimum_balance, interest_rate from wallet_types';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }


 // Get Posts
 public function GetAllUsers()
 {
   // Create query
   
   $query = 'SELECT id, name, email from users';

   // Prepare statement
   $stmt = $this->conn->prepare($query);

   // Execute query
   $stmt->execute();

   return $stmt;
 }

  public function authenticate($key){
    $header_received = base64_decode($key);
    $email = explode(":", $header_received)[0];
    $password = explode(":", $header_received)[1];

    $query = 'SELECT * FROM users WHERE email = ? and password = ? LIMIT 1';


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $password);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [false, 'Authentication failed'];
    }
    return [true, $row];  
  } 


   /*************CreateUserWallet*********************/
   public function CreateUserWallet($userId)
   {
     // Create User query
     $generated = rand(100000000, 999999999);
     $status = '0'; //till accoount is funded
     $query = "INSERT INTO user_wallets SET user_id = $userId, wallet_type = $this->wallet_type_id, wallet_id=$generated, balance = 0, status = $status";
 
     // Prepare statement
     $stmt = $this->conn->prepare($query);
 
     if ($stmt->execute()) {
       return true;
     }
 
     // Print error if something goes wrong
     printf("Error: %s.\n", $stmt->error);
 
     return false;
   }


  // Get All 
  public function GetAllWallets()
  {
    $query = "SELECT a.wallet_id, a.balance, a.status, b.name as wallet_type, c.name as wallet_owner_name, c.email as wallet_owner_email, b.minimum_balance FROM user_wallets a
    join wallet_types b on a.wallet_type = b.id join users c on c.id = a.user_id";

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }


  public function getUser(){
    $email = $this->email;

    $query = 'SELECT * FROM users WHERE email = ? LIMIT 1';


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $email);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [false, 'user not found'];
    }
    return [true, $row];  
  } 

  public function getWallet($walletId = ''){


    $wallet_id = ($walletId == '') ? $this->wallet_id : $walletId;

    $query = 'SELECT w.id, u.name as owner, u.email as owner_email, w.wallet_id, w.balance, w.status, wt.name as wallet_type, wt.minimum_balance as minimum_balance FROM user_wallets w join users u on u.id=w.user_id join wallet_types wt on w.wallet_type=wt.id WHERE wallet_id = ?';


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $wallet_id);

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [false, 'wallet not found'];
    }
    return [true, $row];  
  } 


  public function getWalletTransactions($walletId)
  {
    // Create query
    $query = "SELECT t.transaction_id, t.reference_no, t.amount, t.transaction_type, t.source_wallet_id, t.beneficiary_wallet_id, t.status, t.created_at, w.wallet_id  FROM transactions t
    join user_wallets w on w.wallet_id = t.source_wallet_id or w.wallet_id = t.beneficiary_wallet_id where w.wallet_id = ? ";


    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $walletId);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (empty($row)) {
      return [];
    } else {
      // Set properties
      return $row;
    }
  }

  
  // Get All 
  public function GetTotalWalletsBalance()
  {
    $query = "SELECT SUM(balance) from user_wallets";

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function GetTotalTransactionVolume()
  {
    $query = "SELECT id from transactions";

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function transfer($source_wallet_id, $beneficiary_wallet_id, $amount, $narrative=''){
        
    $transaction_id = 'PAGE-'. time() . '-' . $source_wallet_id . '|'. $beneficiary_wallet_id;
    $reference_no = time().$source_wallet_id;
    
    //check if it same wallet
    if($source_wallet_id == $beneficiary_wallet_id){
        return [false, 'Cannot transfer into same wallet'];
    }
    
    $get_source_array = $this->getWallet($source_wallet_id);
    if(!$get_source_array[0]){
      return [false, $get_source_array[1] . ". Source wallet not found"];
    }

    $get_beneficiary_array = $this->getWallet($beneficiary_wallet_id);
    if(!$get_beneficiary_array[0]){
      return [false, $get_beneficiary_array[1]. ". Beneficiary wallet not found"];
    }


    if($get_source_array[1]["status"] != true){
      return [false, "wallet status is not active"];
    }

    //check is user have enough balance
    $enough = (($get_source_array[1]["balance"] - $amount) >= $get_source_array[1]['minimum_balance'] ) ? true : false;
    if(!$enough){
      return [false, "Insufficient balance"];
    }

    $debit = $this->creditDebitUser($source_wallet_id, 'debit', $amount);
    if(!$debit){
      $this->createTransaction($transaction_id, $reference_no, $source_wallet_id, $beneficiary_wallet_id, $amount, 'debit', 'failed', $narrative);
      return [false, "transaction failed"];
    }

    $this->createTransaction($transaction_id, $reference_no, $source_wallet_id, $beneficiary_wallet_id, $amount, 'debit', 'pending', $narrative);


    $credit = $this->creditDebitUser($beneficiary_wallet_id, 'credit', $amount);
    if(!$credit){
      $this->createTransaction($transaction_id, $reference_no, $source_wallet_id, $beneficiary_wallet_id, $amount, 'credit', 'failed', $narrative);
      $this->UpdateTransction($transaction_id, $source_wallet_id, 'failed');
      return [false, "failed to credit beneficiary"];
    }
    
    $this->UpdateTransction($transaction_id, $source_wallet_id, 'success');
    $this->createTransaction($transaction_id, $reference_no, $source_wallet_id, $beneficiary_wallet_id, $amount, 'credit', 'success', $narrative);

    return [true, "Transaction Successful"];

}


public function createTransaction($transaction_id, $reference_no, $source_wallet_id, $beneficiary_wallet_id, $amount, $type, $status, $narrative=''){
  
  // Create User query
  $query = "INSERT INTO transactions SET transaction_id= '$transaction_id', reference_no='$reference_no', amount='$amount', transaction_type='$type', source_wallet_id= '$source_wallet_id', beneficiary_wallet_id='$beneficiary_wallet_id', narrative='$narrative', status='$status', created_at=now()";

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  // Execute query
  if ($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
}

public function creditDebitUser($wallet_id, $action, $amount){
  $wallet = $this->getWallet($wallet_id)[1];
  $old_balance = $wallet['balance'];
  if($action == 'credit' || $action == 'debit'){
      if($action == 'credit'){
              $new_balance = $old_balance + $amount;
              
              $update_wallet = $this->UpdateWallet($wallet_id, $new_balance);
              if(!$update_wallet){
                return false;
              }
              return true;
      }else if($action == 'debit'){
        $new_balance = $old_balance - $amount;
              
        $update_wallet = $this->UpdateWallet($wallet_id, $new_balance);
        if(!$update_wallet){
          return false;
        }
        return true;
      }
  }else{
      return false;
  }
}

/*************UpdateWallet*********************/
public function UpdateWallet($wallet_id, $amount='', $status='')
{
  $update_amount = ($amount == '') ? " " : " balance='$amount', ";
  $update_status = ($status == '') ? " " : " status = '$status', ";
  $join = $update_amount . $update_status;
  
  $query = "UPDATE user_wallets SET $join updated_at = now() WHERE wallet_id = '$wallet_id'";

  // Prepare statement
  $stmt = $this->conn->prepare($query);
  // var_dump($stmt);
  if ($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
}

/*************UpdateWallet*********************/
public function UpdateTransction($transaction_id, $source_wallet_id, $status)
{
    
  $query = "UPDATE transactions SET status='$status', updated_at = now() WHERE transaction_id = '$transaction_id' AND source_wallet_id = '$source_wallet_id'";

  // Prepare statement
  $stmt = $this->conn->prepare($query);

  if ($stmt->execute()) {
    return true;
  }

  // Print error if something goes wrong
  printf("Error: %s.\n", $stmt->error);

  return false;
}
}