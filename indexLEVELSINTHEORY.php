<?php
//wa wa wa wa! Levels manze

//text is blank = Level 0
//second text is not empty but does have * = level 1
//third text has one star = level 2

$text= $_REQUEST['text'];

$result =  getLevel($text);

$level = $result['level'];
$message = $result['latest_message'];

//main switch board;
switch (strtolower($level)) {
    case 0:
    $response = getHomeMenu();
        break;
    case 1:
    $response = getLevelOneMenu($message);
     break;
    case 2:
    $response = getLevelTwoMenu($message);
      break;
    case 3:
    $response = getLevelThreeMenu($message);
      break;
    default:
     $response = getHomeMenu();
    break;
}

sendOutput($response,1);
exit;

$exploded_text = explode('*',$text);

print_r($exploded_text);
exit;


$input = getInput();

if ( $input['text'] == "" ) {
     // This is the first request. Note how we start the response with CON
  	$response  = " (you are at menu 0)\nLevel 0: 1. Loan".PHP_EOL;
  	$response  .= "Level 0: 2. M-Shwari Balance";
	 sendOutput($response,1);
}else{

  switch (strtolower($input['text'])) {
      case 1:
      $response  = "Level 1: 1. Request Loan (you are at menu 1 from menu 0 option 1 loans)".PHP_EOL;
      $response  .= "Level 1: 2. Pay Loan";
      break;
      
	  case 2:
      $response = "Level 1: 2.1. Your balance is Ksh. 235. (you are at menu 1 from menu 0 option 2 balance)";
      sendOutput($response,2);
      break;
      
	  default:
      $response = "We could not understand your response (you are at menu 1)";
      break;
  }
  sendOutput($response,1);
}

//this is the initial menu to begin with; call it ground zero
//here you have 2 options; loan or balance
function getHomeMenu(){
  $response  = "(You are at menu 0)\nLevel 0: 1. Loan".PHP_EOL;
  $response  .= "Level 0: 2. M-Shwari Balance";
  return $response;
}

//this is the next menu after ground zero; call it menu 1
//here if you choose 1 in the previous menu, it shall open case 1 as the menu
//if you choose 2 in the previous menu, it shall open case 2 as the menu
function getLevelOneMenu($text){

  switch (strtolower($text)) {
      case 1:
      $response  = "Level 1: 1. Request Loan (you are at menu 1 from menu 0 option 1 loans)".PHP_EOL;
      $response  .= "Level 1: 2. Pay Loan";
      break;
      
	  case 2:
      $response = "Level 1: 2.1. Your balance is Ksh. 235. (you are at menu 1 from menu 0 option 2 loans)";
      sendOutput($response,2);
      break;
      
	  default:
      $response = "We could not understand your response (you are at menu 1)";
      break;
  }
  return $response;

}

//this is the next menu after menu 1; call it menu 2
//here if you choose 1 in the previous menu, it shall open case 1 as the menu
//if you choose 2 in the previous menu, it shall open case 2 as the menu
function getLevelTwoMenu($text){

  switch (strtolower($text)) {
      case 1:
      $response  = "Level 2: 1. Enter your details (you are at menu 2 from menu 1 option 1 request loan)".PHP_EOL;
      $response  .= "Level 2: 2. Confirm details";
      break;
      
	  case 2:
      $response = "Level 2: 2.1. Confirming details (you are at menu 2 from menu 1 option 2 pay)";
      sendOutput($response,2);
      break;
      
	  default:
      $response = "We could not understand your response (you are at menu 2)";
      break;
  }
  return $response;

}

//this is the next menu after menu 2; call it menu 3
//here if you choose 1 in the previous menu, it shall open case 1 as the menu
function getLevelThreeMenu($text){

  switch (strtolower($text)) {
      case 1:
      $response  = "Level 3: 1. (you are at menu 3 from menu 2 option 1 enter details)";
      break;
	  
      case 2:
      $response  = "Level 3: 2. (you are at menu 3 from menu 2 option 2 confirm)";
      break;
      
	  default:
      $response = "We could not understand your response (you are at menu 3)";
      break;
  }
	sendOutput($response,2);
  return $response;

}

//verify if the id belongs to one of the staff members
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

?>
