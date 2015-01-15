<?php
header('HTTP/1.1 200 OK');
require('../../../wp-blog-header.php');
global $wpdb;
$table_name = $wpdb->prefix . 'transactions';
ini_set('log_errors', true);
ini_set('error_log', TEMPLATEPATH . '/ipn_errors.log');

// we call to the paypal listener ipn class
include_once(TEMPLATEPATH . '/scripts/ipnlistener.php');
$listener = new IpnListener();

$listener->use_sandbox = (get_option("9t5_pp_sandbox") == "true") ? true : false;

try {
 $listener->requirePostMethod();
 $verified = $listener->processIpn();
} catch (Exception $e) {
 error_log($e->getMessage());
}

// processIpn() return true if IPN was "VERIFIED" and false if not
if ($verified) {
 // if is completed or is pending (if is pending the money is on paypal, and moving to the business account)
 if ($_POST['receiver_email'] != get_option("9t5_pp_email") ||
  $_POST['mc_gross'] != get_option("9t5_pp_cost") ||
  $_POST['mc_currency'] != strtoupper(get_option("9t5_currency"))
 ) {
  exit();
 }

 if ((strtoupper($_POST['payment_status']) == "COMPLETED" || strtoupper($_POST['payment_status']) == "PENDING")) {
  // create a new connection
  $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (mysqli_connect_errno()) {
   error_log("Fail the connection, #bigFail :D, error: " . mysqli_connect_errno());
  } else {
   // now, we create the table if not exists, obvious :D
   if ($mysqli->query("CREATE TABLE IF NOT EXISTS $table_name (
                transaction_id INT NOT NULL auto_increment PRIMARY KEY,
                user_id INT NOT NULL,
                credit_used INT NOT NULL,
                txn_id VARCHAR(19) NOT NULL,
                payer_email VARCHAR(75) NOT NULL,
                payment_status VARCHAR(75) NOT NULL,
                mc_gross FLOAT(9,2) NOT NULL,
                UNIQUE INDEX (txn_id))")
   ) {

    // Creating the insert
    if ($stmt = $mysqli->prepare("INSERT INTO $table_name (txn_id,user_id,credit_used,payer_email,payment_status,mc_gross) VALUES (?,?,?,?,?,?)")) {
     $txn_id = $_POST['txn_id'];
     $user_id = $_POST['custom'];
     $credit_used = 0;
     $payer_email = $_POST['payer_email'];
     $payment_status = $_POST['payment_status'];
     $mc_gross = $_POST['mc_gross'];
     $stmt->bind_param("siissd", $txn_id, $user_id, $credit_used, $payer_email, $payment_status, $mc_gross);
     // executing the query
     $inserts = $stmt->execute();
     //closing
     $stmt->close();
    }
   }
  }
  /* Closing the Connection */
  $mysqli->close();
 }
}
?>