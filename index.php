<?php

//connection to the database
require_once('connection.php');

$phoneNumber = "0".substr(trim($_GET["phoneNumber"]),3,9);

//start
$text = $_REQUEST['text'];

$result =  getLevel($text);

$level = $result['level'];
$message = $result['latest_message'];
global $name, $dob, $location, $pwds, $phoneNumber;


switch (strtolower($level)) {
    case 0:
	$result = getMember($phoneNumber);
	$phonecheck = $result['phoneNumber'];
	if ($phoneNumber == $phonecheck){
			$response = getHomeMenu2();
			sendOutput($response,2);
			exit;
	}
    $response = getHomeMenu();
        break;
    case 1:
    $response = getLevelOneMenu($message);
	$result = createMember($phoneNumber,$name);
     break;
    case 2:
     $response = getLevelTwoMenu($message);
	//take $dob
	$result = updateMemberDob($phoneNumber,$dob);
      break;
    case 3:
     $response = getLevelThreeMenu($message);
	//take $location
	$result = updateMemberLocation($phoneNumber,$location);
      break;
    case 4:
     $response = getLevelFourMenu($message);
	 //take $pwds
	$result = updateMemberPassword($phoneNumber,$pwds);
      break;
    case 5:
     $response = getLevelFiveMenu($message);
      break;
    default:
     $response = getHomeMenu();
    break;
}
sendOutput($response,1);
exit;

$exploded_text = explode('*',trim($text));
print_r($exploded_text);
exit;


//1*1*3*5
$input = getInput();


if ( $input['text'] == "" || $input['text'] == " " ) {
  	$response  = "Sorry :( we do not accept blank replies!!"; //$_GET["phoneNumber"].
  //$name = $text;
	 sendOutput($response,2);
}

function getInput(){
$input = array();
$input['sessionId']   = $_REQUEST["sessionId"];
$input['serviceCode'] = $_REQUEST["serviceCode"];
$input['phoneNumber'] = $_REQUEST["phoneNumber"];
$input['text']        = $_REQUEST["text"];
return $input;
}

function getLevel($text){
  if($text == ""){
    $response['level'] = 0;
  }else{
    $exploded_text = explode('*',$text);
    $response['level'] = count($exploded_text);
    $response['latest_message'] = end($exploded_text);
  }
  return $response;
}

function getHomeMenu2(){
  	//global $phoneNumber; //global $dynamicId;
	$phoneNumber = "0".substr(trim($_GET["phoneNumber"]),3,9);
	$result = getMember($phoneNumber);
	$name = $result['name'];
	$response  = "Welcome back ".$name."! Kindly check with us later as our team processes your current details."
	."\nThank you for staying in touch"; //$_GET["phoneNumber"].
	return $response;
}

function getHomeMenu(){
  	global $phoneNumber; //global $dynamicId;
	$response  = "Welcome our Dear Visitor! Please enter your name:"; //$_GET["phoneNumber"].
	$phoneNumber = "0".substr(trim($_GET["phoneNumber"]),3,9);
  return $response;
}

function getLevelOneMenu($text){
	global $name;
  		$response  = "Hi ".$text.", Enter your date of birth (Year, Month, Date in this format -> YYYYMMDD):";
  		$name = $text;
  return $response;
}

function getLevelTwoMenu($text){
	global $dob;
  		$response  = "Next, which part of Kenya do you live in:"; //$text.
  		$dob = $text;
  return $response;
}

function getLevelThreeMenu($text){
	global $location;
  $response  = "Kindly choose a password so as to access next time round:";
  		$location = $text;
  return $response;
}

function getLevelFourMenu($text){
	global $pwds;
  	$pwds = $text;
  	$phoneNumber = "0".substr(trim($_GET["phoneNumber"]),3,9);
	$result = getMember($phoneNumber);
	$name = $result['name'];
	$dob = $result['dob'];
	$location = $result['location'];
  $response  = "Please confirm and reply back with:\n1. Its all good\n2. All is not well\nYour name (".$name."),\nDate of birth (".$dob."),\nLocation (".$location.")\n*TestingONLY* (".$pwds.")";
  return $response;
}

function getLevelFiveMenu(){
  		$response  = "Thank you for using our system!"; //$text.
sendOutput($response,2);
  return $response;
}

function sendOutput($message,$type=2){
	//Type 1 is a continuation, type 2 output is an end
	if($type==1){
		echo "CON ".$message;
	}elseif($type==2){
		echo "END ".$message;
	}else{
		echo "END We faced an error";
	}
	exit;
}
//create members
function createMember($phoneNumber,$name){

  $query = mysql_query("INSERT INTO members (phoneNumber,name)
  VALUES ('$phoneNumber','$name')");

  return $query;
}

//get members
function getMember($phoneNumber){
    $query = mysql_query("SELECT * FROM members WHERE phoneNumber='$phoneNumber'");
    if (mysql_num_rows($query) > 0) {
        $row = mysql_fetch_assoc($query);
    } else {
      $row['phoneNumber'] = 0;
    }
   return $row;
}

//delete members
function deleteMember($dynamicId){
    $query = mysql_query("DELETE FROM members WHERE phoneNumber='$phoneNumber'");
	return $query;
	}

//update members
function updateMemberName($phoneNumber){
    $query = mysql_query("UPDATE members SET name = '$name' WHERE phoneNumber='$phoneNumber'");
	return $query;
	}
function updateMemberDob($phoneNumber,$dob){
    $query = mysql_query("UPDATE members SET dob = '$dob' WHERE phoneNumber='$phoneNumber'");
	return $query;
	}
function updateMemberLocation($phoneNumber,$location){
    $query = mysql_query("UPDATE members SET location = '$location' WHERE phoneNumber='$phoneNumber'");
	return $query;
	}
function updateMemberPassword($phoneNumber,$pwds){
    $query = mysql_query("UPDATE members SET password = '$pwds' WHERE phoneNumber='$phoneNumber'");
	return $query;
	}
function updateMember($phoneNumber){
  $query = mysql_query("UPDATE members SET phoneNumber ='$phoneNumber' WHERE phoneNumber='$phoneNumber'");
	return $query;
	}
?>
