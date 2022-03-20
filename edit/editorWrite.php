<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

session_start();

include "../incl/connect.php";
//first check if GET SG otherwise will overwrite entries with NULL 
//echo $_GET['q'];
if(isset($_GET['q'])){    
$jumboData = explode(",", $_GET['q']);    
$sql = "UPDATE onlineshop_frontend SET title=?, description=?, button=? WHERE section='jumbotron'";    
    if($stmt = $conn->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("sss", $param_title, $param_desc, $param_btn);
        $param_title = $jumboData[0];
        $param_desc = $jumboData[1];
        $param_btn = $jumboData[2];
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            // Redirect to login page
            echo "all good";
        }          
    } 
}

?>