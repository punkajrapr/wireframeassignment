<?php
	define('SERVERNAME','localhost');
	define('USERNAME','root');
	define('PASSWORD','');
	define('DATABASENAME','WIREFRAME');

	
try {
    $conn = new PDO("mysql:host=".SERVERNAME.";dbname=".DATABASENAME, USERNAME, PASSWORD);
      //echo "CONNECTION ESTABLISHD";
    }
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
?>