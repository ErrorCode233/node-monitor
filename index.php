<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
date_default_timezone_set("Asia/Hong_Kong");

require('EasyPDO.php');

$post_json = json_decode($HTTP_RAW_POST_DATA, true);
$easyPDO = new EasyPDO();

$public_white_list = array('fflog', 'nodelog', 'usercount');

if(in_array(getparam('api'), $public_white_list)){
	call_user_func($post_json['api']);
	// for debug
	echo $HTTP_RAW_POST_DATA;
}else{
	return_error_msg('No such api found:'+getparam('api'));
	//echo $HTTP_RAW_POST_DATA;
}

$easyPDO->closeDB();
exit();

/*
	functions
*/

function getparam($key){
	global $post_json;
	return isset($post_json[$key])?$post_json[$key]:'';
}

function return_error_msg($msg)
{
	echo json_encode(array(
		'error' => $msg
	));
}

function now(){
	$now = new DateTime();
	return $now->format('Y-m-d H:i:s');
}


function fflog(){
	global $easyPDO;

	$easyPDO->insertItem('fflog', array(
		'ffip' => $_SERVER['REMOTE_ADDR'],
		'port' => getparam('port'),
		'time' => now(),
		'log' => getparam('log')
		));
}

function nodelog(){
	global $easyPDO;

	$easyPDO->insertItem('nodelog', array(
		'nodeip' => $_SERVER['REMOTE_ADDR'],
		'port' => getparam('port'),
		'time' => now(),
		'log' => getparam('log')
		));
}

function usercount(){
	global $easyPDO;

	$easyPDO->insertItem('usercount', array(
		'nodeip' => $_SERVER['REMOTE_ADDR'],
		'port' => getparam('port'),
		'time' => now(),
		'number' => getparam('count')
		));
}

?>