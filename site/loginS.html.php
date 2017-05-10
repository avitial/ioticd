<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">    
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">    
  <head>    
    <title>Admin Login Page</title>    
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>    

  </head>    
<!--
Things to add to this page:
  - Generate graph on a selected table with a button. Form field submits values to index.php to pull table and display on another html file.  
  - Form fields to add new data into irrigation/inputs table. For the sake of presentation, a simple 2 input for will suffice

Inserting new data input needs to add temp, humidity, moisture. Date auto adds
-->
  <body>    
  <div> <?php echo $output; ?></div>
  
    <form action="index.php" action method="post">   
      <div>   
        <label for="NewTemp">Temperature:
        <br>
          <input type="text" name="NewTemp" id="NewTemp" rows="1" cols="10"/>
        </label>     
      </div>  
         
      <div>   
        <label for="NewHum">Humidity:
        <br> 
          <input type="text" name="NewHum" id="NewHum" rows="1" cols="10"/>
        </label>     
      </div> 

      <div>   
        <label for="NewMoi">Moisture:
        <br> 
          <input type="text" name="NewMoi" id="NewMoi" rows="1" cols="10"/>
        </label>     
      </div> 

      <div>
        <input type="submit" value="Add" name="datasetNrow"/>
      </div>   
    </form> 
    
    <br><br>
    <!-- _______________________________________________________ Test user ______________________________________________ -->
    <form action="index.php" action method="post">
      <div>
        <label for="Test Connection">MySQL user:
          <br>
          <input type="text" name="MyUser" id="MyUser">
        </label>
      </div>
        <br>
      <div>
        <label for="Test Connection">MySQL password:
          <br>
          <input type="text" name="MyPwd" id="MyPwd">
        </label>
      </div>
      
      <div>
        <input type="submit" value="GO" name="GO"/>
      </div>
    </form> 

<br><br>
<!-- __________________________________________________ Table Graph _________________________________________________ -->
    <form action="index.php" action method="post">
      <p>
        <input type="submit" value="Show Table Graph" name="TableGraph"/>
      </p>  
    </form>

<br><br>
<!-- __________________________________________________  ____________________________________________________ -->
  </body>    
</html>