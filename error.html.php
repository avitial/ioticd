<!--
__________________________________Notes____________________________
gi2: Separate error detection page. 

___________________________________________________________________
-->

<?php
//pull link all from central area when done configuring everything. Currently, the $link to the msql database is hardcoded
$link = mysqli_connect('localhost', 'root', ''); 

if(!$link){  
  $error = 'Unable to connect to the database server.';  
  //include 'error.html.php';  
  echo $error;
  exit();  
}  

if(!$link)  
{  
  $error = 'Unable to connect to the database server.';  
  //include 'error.html.php';  
  echo $error;
  exit();  
}  
  
if(!mysqli_set_charset($link, 'utf8'))  
{  
  $error = 'Unable to set database connection encoding.';  
  //include 'output.html.php';  
  echo $error;
  exit();  
}  
  
if(!mysqli_select_db($link, 'irrigation'))  
{  
  $error = 'Unable to locate the irrigation database.';  
  //include 'error.html.php';  
  echo $error;
  exit();  
}  

?>