<?php
ob_start();
error_reporting(0);
session_start();
include 'database.php';

$error = ""; //error holder
 
 $files = array('2.png','13592189_10153670853327765_1959899751804612407_n.jpg','GH.PNG');
$file_folder = "uploads/"; // folder to load files
if(extension_loaded('zip'))
{ 
// Checking ZIP extension is available
 
// Checking files are selected
$zip = new ZipArchive(); // Load zip library 
$zip_name = time().".zip"; // Zip name
if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE)
{ 
 // Opening zip file to load files
$error .= "* Sorry ZIP creation failed at this time";
}

$csv_file = time()."_csvdata.csv";
$file = fopen("csv/$csv_file"	,"w");
fputcsv($file,explode(',',"Post Title, Post Image"));
foreach ($list as $line)
  {
  fputcsv($file,explode(',',$line));
  }




$stmt = $conn->prepare("SELECT post_name, post_image FROM tbl_post"); 
$stmt->execute();
	
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
foreach(new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v) {
	 
		$zip->addFile($file_folder.$v['post_image']); // Adding files into zip
		fputcsv($file,explode(',',"".$v['post_name'].", ".$v['post_image']));
	}
	$zip->addFile("csv/$csv_file");
fclose($file);


	
$zip->close();

unlink("csv/$csv_file");
if(file_exists($zip_name))
{
// push to download the zip
header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.$zip_name.'"');
readfile($zip_name);
// remove zip file is exists in temp path
unlink($zip_name);
}
 


}

