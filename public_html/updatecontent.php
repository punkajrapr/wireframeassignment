<?php
ob_start();
error_reporting(0);
session_start();
include 'database.php';

$stmtAjax = $conn->prepare("SELECT 1 FROM tbl_post"); 
$stmtAjax->execute();
$viewstmtAjax = $conn->prepare("SELECT 1 FROM tbl_session"); 
$viewstmtAjax->execute();


echo '{"Posts": "Posts: '.$stmtAjax->rowCount().'","Views":"Views: '.$viewstmtAjax->rowCount().'"}';