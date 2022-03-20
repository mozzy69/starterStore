<?php
session_start();
include "../incl/connect.php";
//Set loggedInUser variable to username session super global
if(isset($_SESSION["username"])){
    $loggedInUser = $_SESSION["username"];
    }
?>

<!DOCTYPE html>
<html lang="">

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>The Startup Store App Editor</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Custom stylesheet here-->
    <link href="../css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
	<header></header>
	<main>
<form>        
<!--Edit Jumbotron-->
<div class="jumbotron"> 
<div class="container">    
<?php
    
$sql = "SELECT * FROM onlineshop_frontend WHERE section='jumbotron'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
//echo  .  . $row["main_button"]; 
$jumbotronTitle = $row["title"];
$jumbotronDescription = $row["description"];
$jumbotronButton = $row["button"];        
        
echo <<<_END
            <div class="row">
            <h1 class="display-4"><input type="text" class="form-control col-12" id="jumbotronTitle" placeholder="$jumbotronTitle"></h1>
            <textarea class="form-control col-12" id="jumbotronDescription">$jumbotronDescription</textarea>
            <div class="btn btn-light"><input type="text" class="form-control col-12" id="jumbotronButton" value="$jumbotronButton"></div>
            </div><!--end row-->
_END;
   
?>

    <div class="row">
        <div class="col">
            <button class="btn btn-primary float-right" onclick="updateHeader()">Update Header</button>    
        </div>
    </div>    
</div>
</div><!--end jumbotron-->    
</form>    
 
<form action="upload.php" method="post" enctype="multipart/form-data" class ="col-12">        
<!--Edit Jumbotron-->
<div class="jumbotron">
    <div class="container">
<?php

$sql = "SELECT * FROM onlineshop_frontend WHERE section='about'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
//echo  .  . $row["main_button"]; 
$aboutTitle = $row["title"];
$aboutDescription = $row["description"];

//img upload      
        
echo <<<_END
        
            <div class="row">
            <h2 class="display-8"><input type="text" class="form-group col-12" id="aboutTitle" placeholder="$aboutTitle"></h2>
            </div><!--end row-->
            <div class="row">
            <textarea class="form-control col-8" id="aboutDescription">$aboutDescription</textarea>
            <div class="form-group col-4">
              <label for="fileToUpload" >Add an About Section Image : </label>
              <input type="file" class="form-control-file" name="fileToUpload" id="fileToUpload">
            </div><!--end form group-->
            </div><!--end row-->    
          
_END;
        
?>
         <div class="row">
            <div class="col">
                <input type="submit" class="btn btn-primary float-right" value="Update About" name="submit">    
            </div>
        </div><!--end Update About row-->
    </div><!--end container-->     
</div><!--end jumbotron-->    
</form>    

<br>         
   
<?php
/*         
    if(file_exists("dadir")){
        echo "sorry already exists";
    }else{
        mkdir("dadir");
    };
*/         
?>    
       
    </main>
	<footer></footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="../scripts/SSeditor.js"></script>
</body>



</html>