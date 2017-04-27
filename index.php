<!--
_______________________________________________Notes__________________________________
Gi1 (good idea 1): Make a second landing page for a successful login.
  -this page will have anumber of buttons on it. Each button runs different functions of php. 
    like 'load current dataset' | 'edit values' | 'generate graph'

______________________________________________________________________________________
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
  <head>  
    <title>List of Jokes</title>  
    <meta http-equiv="content-type"  
        content="text/html; charset=utf-8"/>  
  </head>  
  <body>  
    <p>Display of MySQL 'inputs' Database. </p> 
  </body>  
</html>

<?php 

include('error.html.php');

//include('idata.html.php');

$link = mysqli_connect('localhost', 'root', ''); 
$result = mysqli_query($link, 'SELECT Temp FROM inputs');  

if(!$result){  
  $error = 'Error fetching data: ' . mysqli_error($link);  
  include 'error.html.php';  
  exit();  
}  

//Used to print array Temp (database assigned name) 
while($row = mysqli_fetch_array($result)){  
  $Temp['row'] = $row['Temp'];  
  echo $Temp['row'];
  nl2br("");
}  
  
/*
while($row = mysqli_fetch_array($result)){  
  $data[] = $row['Temp'];  
  echo htmlspecialchars($data[row], ENT_QUOTES, 'UTF-8');   
} 
*/

// Determine whether something was submitted to the field NewTemp. If(True), table/column/NewData values are placed in $sql
if(isset($_POST['NewTemp'])){
  $NewData = mysqli_real_escape_string($link, $_POST['NewTemp']);   
  $sql = 'INSERT INTO inputs SET Temp= "' . $NewData . '" ';  
}

/*  // Uncomment This area to submit data onto a new row within table 'inputs' 

//If $link, and $sql are not set, the error will be set. If both values were set properly, $sql updates database. 
if(!mysqli_query($link, $sql)){
  //error displayed if $sql data cannot be added to $link database.    
  $error = 'Error adding submitted temp: ' . mysqli_error($link);   
  include 'error.html.php';   
  exit();   
}   
*/

?>