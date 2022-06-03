<?php
/* 
    Template Name: API Update Cards
*/
header("Content-Type:application/json");

$message = $_POST['message'];

$data   = array();

$data['error'] = 0;
$data['message'] = $message;



$json_response = json_encode($data);
echo $json_response;


