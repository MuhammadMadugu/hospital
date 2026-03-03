<?php
include '../functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || ($_SESSION['type'] != 0  AND $_SESSION['type'] != 7)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Read JSON payload
$input = file_get_contents('php://input');
$data  = json_decode($input, true);

$payment_id     = intval($data['payment_id'] ?? 0);
$amount         = floatval($data['amount'] ?? 0);
$discount       = floatval($data['discount'] ?? 0);
$net_amount     = floatval($data['net'] ?? 0);
<<<<<<< HEAD
$payment_method = !empty($data['payment_method']) ? sanitize($data['payment_method']) : '';
 $reciept_num = generateReceiptNumber($db);

=======
$payment_method = sanitize($data['payment_method']);
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5

if ($payment_id <= 0 || $net_amount <= 0 || empty($payment_method)) {
    echo json_encode(['success' => false, 'message' => 'Invalid payment data']);
    exit;
}

/* =========================
   FETCH PAYMENT
========================= */
$payQ = $db->query("SELECT * FROM payments WHERE id = '$payment_id' LIMIT 1");
if ($payQ->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Payment record not found']);
    exit;
}

$payment = $payQ->fetch_assoc();
$patient_id = $payment['patient_id'];

<<<<<<< HEAD


=======
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5
if(($_SESSION['type']!= 0) AND ($payment['amount'] != $amount OR $payment['net_amount']!=$net_amount OR $payment['discount']!=$discount)){
    echo json_encode(['success' => false, 'message' => 'An Error Occured']);
    exit;
}

// Prevent double payment
if ($payment['status'] == 1) {
    echo json_encode(['success' => false, 'message' => 'Payment already completed']);
    exit;
}

/* =========================
   UPDATE PAYMENT
========================= */
$user_id = getId();
<<<<<<< HEAD
$sql = " UPDATE payments SET
    amount = '$amount',
    discount = '$discount',
    net_amount = '$net_amount',
    `payment-method` = '$payment_method',
    payment_date = NOW(),
    accountant_id = '$user_id',
    status = 1,
    reciept_num = CASE 
                     WHEN reciept_num  IS NULL OR reciept_num = '' 
                     THEN '$reciept_num' 
                     ELSE reciept_num 
                  END
WHERE id = '$payment_id';";
$update = $db->query($sql);
=======

$update = $db->query("
    UPDATE payments SET
        amount = '$amount',
        discount = '$discount',
        net_amount = '$net_amount',
        `payment-method` = '$payment_method',
        payment_date = NOW(),
        accountant_id = '$user_id',
        status = 1
    WHERE id = '$payment_id'
");
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5

if (!$update) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to update payment',
        'error'   => $db->error
    ]);
    exit;
}

/* =========================
   POST-PAYMENT ACTIONS
========================= */

<<<<<<< HEAD
// If DRUG payment → mark patient_drugs as paid + mark admission billing drug items
if($payment['purpose'] == 2){

  $db->query("
    UPDATE patient_drugs
    SET status = 1
    WHERE payment_id = '$payment_id'
");

  // Also mark admission billing drug items as paid (for admitted patients)
  $db->query("UPDATE admission_billing SET paid = 1 WHERE billing_type = 2 AND reference_id = '$payment_id'");

=======
// If DRUG payment → mark patient_drugs as paid
if($payment['purpose'] == 2){
  
  $db->query("
    UPDATE patient_drugs 
    SET status = 1 
    WHERE payment_id = '$payment_id'
");

>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5

}else if($payment['purpose'] == 3){
  
   // If LAB payment → mark patient_test as paid
$db->query("
    UPDATE patient_test 
    SET status = 1 
    WHERE payment_id = '$payment_id'
");

// get the patient_test

$sql = "SELECT id FROM patient_test WHERE payment_id = '$payment_id'";
$run = $db->query($sql);
$myInfo = $run->fetch_assoc();
$patient_test_id = $myInfo['id'];

$db->query("
    UPDATE test_lists
    SET status = 1 , paid = 1
    WHERE patient_test_id = '$patient_test_id'
");




}else if($payment['purpose'] == 1){
<<<<<<< HEAD

=======
 
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5
  $db->query("
    UPDATE users
    SET status = 1
    WHERE id = '$patient_id'
");

<<<<<<< HEAD
}else if($payment['purpose'] == 4){
    // Admission billing item payment - mark the billing item as paid
    $db->query("UPDATE admission_billing SET paid = 1 WHERE payment_id = '$payment_id'");
}else if($payment['purpose'] == 5){
    // Radiology payment - mark patient_scan and scan_lists as paid
    $db->query("UPDATE patient_scan SET status = 1 WHERE payment_id = '$payment_id'");
    $scanInfo = $db->query("SELECT id FROM patient_scan WHERE payment_id = '$payment_id'")->fetch_assoc();
    if ($scanInfo) {
        $patient_scan_id = $scanInfo['id'];
        $db->query("UPDATE scan_lists SET status = 1, paid = 1 WHERE patient_scan_id = '$patient_scan_id'");
    }
    // Also mark admission billing scan items as paid
    $db->query("UPDATE admission_billing SET paid = 1 WHERE billing_type = 3 AND reference_id = '$payment_id'");
}else if($payment['purpose'] == 6){
    // Consultation fee payment - activate the appointment
    $appointment_id = $payment['appointment_id'];
    if ($appointment_id > 0) {
        $db->query("UPDATE appointments SET status = 0 WHERE id = '$appointment_id' AND status = -1");
    }
=======
>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5
}






<<<<<<< HEAD
=======


>>>>>>> ebc253a72e4a128f805e4199017270518a535eb5
echo json_encode([
    'success' => true,
    'message' => 'Payment saved successfully'
]);
exit;
?>
