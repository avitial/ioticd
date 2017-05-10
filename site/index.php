<!--
____________________________________________________Notes________________________________________________________
-Add a dropdown on loginS. Add a GET request for every dropdown value that will show the desired table. 
-Connect to database button to be moved to loginS.html.php. These values should run before anything else, to setablish a
  connection. 
- Need to add success alert when new data is added. User will be kept on same loginS page. (new data fields will be emptied)



_________________________________________________________________________________________________________________
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
  <head>  
    <title>IndexPHP</title>  
    <meta http-equiv="content-type"  content="text/html; charset=utf-8"/>  
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  </head>  
  <body>  
  </body>  
</html>

<?php 
// if the Submit button has been pressed, this code will run. 
if(isset($_POST['Submit'])){ 
  //User & pwd are pulled from inputs from index.html and tested. If successful, user sent to loginS.html page
  $user = $_REQUEST['User'];
  $pwd = $_REQUEST['Password'];

  if ( ($user != 'User' & $pwd == 'pwd') ) {
    $output = 'Invalid User';
    include 'output.html.php';
    exit();
  }
  elseif ( ($user == 'User' & $pwd != 'pwd') ) {
    $output = 'Invalid Password';
    include 'output.html.php';
    exit();
  }
  elseif( ($user != 'User' & $pwd != 'pwd') ){
    $output = 'Incorrect user information. Please check user and password.';
    include 'output.html.php';
    exit();
  }
  elseif(($user == 'User')&($pwd == 'pwd')){
    include 'loginS.html.php';
    header('Location: loginS.html.php');
    exit();
  }
}
//__________________________________________________Connect To Database_________________________________________

//Checks to see if GO was pressed. If successful, will check connection to Database. 
if(isset($_POST['GO'])){
  $link = mysqli_connect('locahost', 'root', '', '');

  if(!$link){
    $error = 'Unable to connect to the database server.';  
    include 'error.html.php';  
    exit();  
  }  
  elseif(!mysqli_set_charset($link, 'utf8')){  
    $error = 'Unable to set database connection encoding.';  
    include 'error.html.php';  
    exit();  
  }  
  elseif(!mysqli_select_db($link, 'irrigation')){  
    $error = 'Unable to locate the irrigation database.';  
    include 'error.html.php';  
    exit();  
    }  
  
  $output = 'Connection Successful!';
  include 'output.html.php';
  exit();
  

}

//____________________________________________________ Writing to table_________________________________________________

if(isset($_POST['datasetNrow'])){
  $link = mysqli_connect('localhost', 'root', '', 'irrigation');

  $NewTemp = $_POST['NewTemp'];
  $NewHum = $_POST['NewHum'];
  $NewMoi = $_POST['NewMoi'];
  $NewData = (" $NewTemp, $NewHum, $NewMoi, CURDATE() ");
  $sql = "INSERT INTO dataset (Temp, Humidity, Moisture, Date) Values ($NewData)";  
//If $link, and $sql are not set, the error will be set. If both values were set properly, $sql updates database. 
  if(!mysqli_query($link, $sql)){   
    $error = 'Error adding submitted temp: ' . mysqli_error($link);   
    include 'error.html.php';   
    exit();   
  }
  /*
  else{
    $output = 'NEW ROW SUCCESSFULY ADDED';
    include 'loginS.html.php';
    exit();
  } 
  */  
}
//_________________________________________________Displaying Data in Table_____________________________________________

if(isset($_POST['TableGraph'])){
  $link = mysqli_connect('localhost', 'root', '', 'irrigation');
  $query = "SELECT id, Temp, Humidity, Moisture FROM dataset"; 
  $qResults = mysqli_query($link, $query);
  while($row = mysqli_fetch_assoc($qResults)){
    $qid[] = $row['id'];
    $qT[] = $row['Temp'];
    $qH[] = $row['Humidity'];
    $qM[] = $row['Moisture'];
    //echo $row['Temp'].", ";
  }
  //echo '<BR><BR>Integer Data: <BR><BR>';
  for($i=0; $i<sizeof($qT); $i++){
    $qid[$i] =(int)$qid[$i];
    $qT[$i] = (int)$qT[$i];
    $qH[$i] = (int)$qH[$i];
    $qM[$i] = (int)$qM[$i];
    echo $qid[$i] ." ". $qT[$i] ." ". $qH[$i] ." ". $qM[$i]."<BR>";
  }
  include 'GraphPage.html.php';
  exit();
}
//_____________________________________________Displaying a list of tables________________________________________________
/* IN TESTING
if( isset($_POST['ShowTables']) ) {
  $link = mysqli_connect('localhost', 'root', '');
  $mySel= "SHOW TABLES IN irrigation"; // MySQL query assigned to mySel

  $result = mysqli_query($link, $mySel); // Run query mySel on database from $link

  while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
    echo($table[0] . "<BR>");    // print the table that was returned on that row.
  }
}
*/
?>