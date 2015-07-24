<?php
//receive the sms, phone, Message
require_once('connection2.php');

$sms = getSmsInput();

$staff = getStaff($sms['message']);

if($staff['id'] != 0){
  $reply= "ID is valid and it belongs to ".$staff['first_name']." ".$staff['last_name'];

}else{
 $reply =  "No Staff with that id";
}

//$reply = "Thank you for your SMS";

sendSmsOutput($sms['from'],$reply);
exit;
//send back a message to the phone number_format
function sendSmsOutput($number,$msg){

  			  		//lets add the variables to the records array
  			  		if(is_array($msg)){
  			  			$records[0]= array( 'message' => $msg[0], 'to' => $number[0]);
  						$records[1]= array( 'message' => $msg[1], 'to' => $number[1]);
  			  		}else{
  			  	$records[]= array( 'message' => $msg, 'to' => $number);
  					}
  			  	$sms_array= array();
  				$sms_array[] = array('success'=>"true",'secret'=>"",'task'=>"send",'messages'=>$records);
  				$payload= array('payload'=>$sms_array[0]);
  				header('content-type: application/json; charset=utf-8');
  				echo json_encode($payload);
  				//mysql_close($connect);
  			  }

function getSmsInput(){
  $sms['from'] = $_REQUEST['from'];
  $sms['message'] = trim($_REQUEST['message']);
  return $sms;
}
function getStaff($id){
    $query = mysql_query("SELECT * FROM staff WHERE id='$id'");

    if (mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);
    } else {
      $row['id'] = 0;
    }

   return $row;

}



// $leo = array('name'=>'leo','staff_id'=>1);
//
// $leo = json_encode($leo);
//
// $leo = json_decode($leo);

// print_r($leo);
// exit;


//array str_shuffle
// $staff = array();
// $leo = array('name'=>'leo','staff_id'=>1);
// array_push($staff,$leo);
// //print_r($staff);
//
// //exit;
// $macharia = array('name'=>'Macharia','staff_id'=>2);
// array_push($staff,$macharia);
// //$staff = array($leo,$macharia);
// print_r($staff);
// exit;


 ?>
