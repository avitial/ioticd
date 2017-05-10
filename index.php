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

  if ( ($user != 'David' & $pwd == 'pwd') ) {
    $output = 'Invalid User';
    include 'output.html.php';
    exit();
  }
  elseif ( ($user == 'David' & $pwd != 'pwd') ) {
    $output = 'Invalid Password';
    include 'output.html.php';
    exit();
  }
  elseif( ($user != 'David' & $pwd != 'pwd') ){
    $output = 'Incorrect user information. Please check user and password.';
    include 'output.html.php';
    exit();
  }

  elseif(($user == 'David')&($pwd == 'pwd')){
    include 'loginS.html.php';
    header('Location: loginS.html.php');
    exit();
  }



//  $link = mysqli_connect('localhost', 'root', '');
  //$mySel= "SHOW TABLES IN irrigation"; // MySQL query assigned to mySel
  //$result = mysqli_query($link, $mySel); // Run query mySel on database from $link
  
}
//----------------------------------------------------------------------------------------------------------------------------
//Checks to see if GO was pressed. If successful, will check connection to Database. 
// Prints out data in 
if(isset($_POST['GO'])){
  $link = mysqli_connect('localhost', 'root', '');

  if( !$link ){
    $output = 'Unable to connect to the database server.';  
    include 'output.html.php';  
    exit();  
  }  
  elseif(!mysqli_set_charset($link, 'utf8')){  
    $error = 'Unable to set database connection encoding.';  
    include 'error.html.php';  
    echo $error;
    exit();  
  }  
  elseif(!mysqli_select_db($link, 'irrigation')){  
    $error = 'Unable to locate the irrigation database.';  
    include 'error.html.php';  
    echo $error;
    exit();  
    }  

  $result = mysqli_query($link, 'SELECT Temp FROM inputs'); 
  if(!$result){  
    $error = 'Error fetch data: ' . mysqli_error($link);  
    include 'error.html.php';  
    exit();  
    }  


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
    //error displayed if $sql data cannot be added to $link database.    
    $error = 'Error adding submitted temp: ' . mysqli_error($link);   
    include 'error.html.php';   
    exit();   
  }   

}
//_________________________________________________displaying tables_____________________________________________

if(isset($_POST['TableGraph'])){
  $link = mysqli_connect('localhost', 'root', '', 'irrigation');
  $query = "SELECT id, Temp, Humidity, Moisture FROM dataset"; 
  $qResult = mysqli_query($link, $query);

  while($row = mysqli_fetch_assoc($qResult)){  // for printing single rows, use _array, else use assoc
    $table[] = $row;// array of arrays. Data with their labelse
    //echo($row[1] . ", ");  // a single row of data

  }
  //while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
    //echo($table[0] . "<BR>");    // print the table that was returned on that row.
  //}
   echo json_encode($table);

  //echo '<pre>';
  //print_r($table); // print testing. 
  //echo'</pre>';

/*
if( isset($_POST['ShowTables']) ) {
  $link = mysqli_connect('localhost', 'root', '');
  $mySel= "SHOW TABLES IN irrigation"; // MySQL query assigned to mySel

  $result = mysqli_query($link, $mySel); // Run query mySel on database from $link

  while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
    echo($table[0] . "<BR>");    // print the table that was returned on that row.
  }
}
*/
   //$id['row']   = $row['id']; 
   //$Temp['row'] = $row['Temp'];  
   //printf("id:%d, Temp:%d\n", $id['row'], $Temp['row']);
    //echo json_encode($row);
  //}//while

}

  //$stmp = $link->prepare('SELECT * FROM dataset');
  //$stmp->execute();
  //$results=$stmp->;
  //echo json_encode($results);
  //$mySel= "SHOW TABLES IN irrigation"; // MySQL query assigned to mySel
  //$result = mysqli_query($link, $mySel); // Run query mySel on database from $link

  //$query = "SELECT * FROM dataset"; 
  //$qResult = mysqli_query($link,$query); 
  //$row = mysql_fetch_row($qResult);//($qResult, MYSQLI_ASSOC);
  //echo $row[0];
  //$results=$link->fetchALL(PDO::FETCH_OBJ);
  //echo json_encode($results);


/*
  $qArray = array();
  if($qResult->num_rows > 0 ){
        while($row = $result->fetch_assoc()) {
        $qArrayItem = array();
        $jsonArrayItem['label'] = $row['id'];
        $jsonArrayItem['value'] = $row['Temp'];

          array_push($jsonArray, $jsonArrayItem);
        }
  }
    //Closing the connection to DB
    $conn->close();
    //set the response content type as JSON
    header('Content-type: application/json');
    //output the return value of json encode using the echo function. 
    echo json_encode($jsonArray);

    */


  //while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
    //echo($table[0] . "<BR>");    // print the table that was returned on that row.
  //}


/*
if( isset($_POST['ShowTables']) ) {
  $link = mysqli_connect('localhost', 'root', '');
  $mySel= "SHOW TABLES IN irrigation"; // MySQL query assigned to mySel

  $result = mysqli_query($link, $mySel); // Run query mySel on database from $link

  while($table = mysqli_fetch_array($result)) { // go through each row that was returned in $result
    echo($table[0] . "<BR>");    // print the table that was returned on that row.
  }
}
*/



//-----------------------------------------------------------------------------------

  

//------------------------------------------------------------------------------------  
/*
while($row = mysqli_fetch_array($result)){  
  $data[] = $row['Temp'];  
  echo htmlspecialchars($data[row], ENT_QUOTES, 'UTF-8');   
} 
*/

// check datasetNrow, if pressed, datasest table will be updated with values: newTemp, NewHum, NewMoi, and current date. 

?>