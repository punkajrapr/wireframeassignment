<?php
ob_start();
error_reporting(0);
session_start();
include 'database.php';
$sessionId = session_id();

$stmtAjax = $conn->prepare("SELECT 1 FROM tbl_post where createdby='".$sessionId."'""); 
$stmtAjax->execute();
$viewstmtAjax = $conn->prepare("SELECT 1 FROM tbl_session"); 
$viewstmtAjax->execute();


echo '{"Posts": "Posts: '.$stmtAjax->rowCount().'","Views":"Views: '.$viewstmtAjax->rowCount().'"}';