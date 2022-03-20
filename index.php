<?php

/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

session_start();
include "incl/connect.php";
//Set loggedInUser variable to username session super global
//unset($_SESSION["guestname"]);
if(isset($_SESSION["loggedin"])){
    //unset($_SESSION["username"]);
    $loggedInUser = $_SESSION["username"];
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <title>The Starter Store App</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Custom stylesheet here-->
    <link href="css/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="shoppingApp">
<!------------****************NAV****************------------>    
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <img id="shoppingCartMenu" src="images/cart.png" alt="shopping cart">
    <a class="navbar-brand" href="#">The Starter Store </a>
    <img id="logoNav" src="images/logo_nav_128.jpg" alt="logo">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link align-middle" onclick="homeNavButtonScroll()" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" id="aboutNavButton"  onclick="aboutNavButtonScroll()" href="#">About <span class="sr-only">(about)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">Contact <span class="sr-only">(contact)</span></a>
            </li>
            
            <!--Display LogIn/LogOut buttons based on user login status-->
            <div id="accountLoginOut">
<?php
    if(isset($loggedInUser)){
        //echo "<a class=\"nav-link\" href=\"#\">Account</a>";
        echo "<li class=\"nav-item dropdown\">
                <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"dropdown01\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Account</a>
                <div class=\"dropdown-menu\" aria-labelledby=\"dropdown01\">
                    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#userProfile\" onclick=\"userProfiles()\">Profile</a>
                    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#ordersMain\" onclick=\"userOrders()\">Orders</a>
                    <a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#logoutMain\" onclick=\"logoutUser()\">Log out</a>
                </div>
            </li>";
    }else{
        echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"#\" data-toggle=\"modal\" data-target=\"#loginMain\" data-dismiss=\"modal\">Log in</a></li>";
    }
?>
                
            </div><!--accountLoginOut-->
            <li class="nav-item">
                <div class="alert alert-dark" id="navTotalCost" role="alert" >
  {{toUSD(editCartNewPrice(itemsObj))}} <!--<span class="badge badge-light">{{totalItems}}</span>
  <span class="sr-only">number of items in cart</span>-->
</div>          
            </li>
            <li class="nav-item active px-md-2">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#overlay" @click="duplicateObj(itemsObj)"><span class="pr-2">Cart</span><span class="badge badge-light">{{totalItems}}</span>
  <span class="sr-only">number of items in cart</span></button>
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#checkoutMain" @click="checkout" >Checkout</button>
                    </div>   
            </li>    
        </ul>
    </div>
</nav>
<!------------**************End Nav**************------------>
    <main role="main" class="container-fluid mt-3">
        
        <!--******************************MODALS*********************************-->
        
        <!------------**************Begin Modal Edit Cart**************------------>
        <div class="modal fade" id="overlay" tabindex="-1" role="dialog" aria-labelledby="overlayTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="overlayTitle">Edit Your Shopping Cart</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <div id="editCart">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(item, key, index) in itemsObj" v-if="item.quantity > 0">
                                    <h5 class="display-5 text-center text-secondary font-weight-light">{{ item.short_desc }} </h5>
                                    <div id="deleteItemID">
                                        <button class="close float-left px-2" name="deleteItem" @click="deleteItem()" v-bind:value= key>&times;</button>
                                        <input type="number" v-model=item.quantity min="1" max="5">
                                    </div> 
                                    <p class="float-right">{{toUSD(editCartLineItems(item.quantity, item.price))}}</p>
                                </li>   
                            </ul>
                            <div class="alert alert-secondary" role="alert"><p class="display-5 text-center text-dark align-middle font-weight-normal">{{toUSD(editCartNewPrice(itemsObj))}}</p></div>
                            <div class="btn-group col-12" role="group">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close" @click="upDateEditCart(itemsObj)">Update</button>
                            <!--REMOVED CANCEL BUTTON<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close" @click="resetChanges(quantities, itemsObj)">Cancel</button>-->
                            </div><!--end button group-->
                            <!--Removed Checkout From Here-->    
                        </div><!---end #editcart--->
                    </div><!--modal body-->    
                </div><!--modal content-->    
            </div><!--modal dialog-->    
        </div>
        <!------------**************End Modal Edit Cart**************------------>
        
        <!------------**************Begin Modal Products**************------------>        
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <!--<h5 class="modal-title display-5 text-secondary" id="modalLongTitle">
                ...
                </h5>-->
                <h5 class="display-5 text-secondary font-weight-light" id="prodSecTitle">
                  ...  
                  </h5>  
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="emptyOptions">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modalProductDetails">
                ...
              </div>
              <div class="modal-body">        
                  <!--prodSecTitle was here-->
                  
                  <h4 class="text-center text-secondary display-5 font-weight-light" id="modalProductPrice"></h4>
                      
                  <p id="prodLongDesc">
                  ...
                  </p>
                  <h5 class="display-5 text-secondary font-weight-light" id="prodOptionsCheckboxTitle">
                  ...
                  </h5>
                  <div id="prodOptionsCheckbox">
                  <!--Checkbox options-->
                  </div>
                  
              </div><!--end modal body-->      
              <div id="prodModalFooterButtons" class="modal-footer">
                <!--<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="emptyOptions()">Close</button>-->
                  <button type="button" class="btn btn-primary col-12" onclick="getCheckedBoxes(this)" data-dismiss="modal" id="productsModalButton">Save changes</button>
              </div>
            </div>
          </div>
        </div><!--product modal-->
        <!------------**************End Modal Products**************------------>
        
        <!------------**************Begin Modal CHECKOUT**************------------>
        <div class="modal fade" id="checkoutMain" tabindex="-1" role="dialog" aria-labelledby="checkoutMainTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutMainTitle">Thanks for shopping with us</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <div class="jumbotron jumbotron-fluid px-2 py-0 mt-3">
                            <h2 class="display-5 text-center text-secondary font-weight-light">Checkout</h2>
                            <p class="alert alert-success display-4 text-center text-dark font-weight-light">Your Total is {{toUSD(editCartNewPrice(itemsObj))}}</p>
                            <div id="checkoutProfileInfo">
<?php
  if(isset($_SESSION["username"])){
    $loggedInUser = $_SESSION["username"];
    }else{
      echo "<p>You must <a href=\"#\" data-toggle=\"modal\" data-target=\"#loginMain\" data-dismiss=\"modal\">log in</a> to set your delivery address</p>";
  }                          
?>
                            <form>
                                <hr class="mx-2">
                                <p class="text-muted">Please confirm your contact email</p>
                                <input type="text" class="form-control mb-2" id="CO_inputEmail">
                                <hr class="mx-2">
                                <p class="text-muted">Please confirm the delivery address.</p>
                                <p class= "col-12 mt-3" id="CO_inputConfirm"></p>
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <!--<label for="CO_inputFirstname">Name</label>-->
                                  <input type="text" class="form-control" id="CO_inputFirstname" required placeholder="Name">
                                </div>
                                <div class="form-group col-md-6">
                                  <!--<label for="CO_inputSurname">Surname</label>-->
                                  <input type="text" class="form-control" id="CO_inputSurname" required placeholder="Surname">
                                </div>
                              </div>      
                              <div class="form-group">
                                <!--<label for="CO_inputAddress">Address</label>-->
                                <input type="text" class="form-control" id="CO_inputAddress" required placeholder="Building/Complex Number">
                              </div>
                              <div class="form-group">
                                <!--<label for="CO_inputAddress2">Address 2</label>-->
                                <input type="text" class="form-control" id="CO_inputAddress2" placeholder="Street Number, Suburb">
                              </div>
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <!--<label for="CO_inputCity">City</label>-->
                                  <input type="text" class="form-control" id="CO_inputCity" required placeholder="City">
                                </div>
                                <div class="form-group col-md-4">
                                  <!--<label for="CO_inputState">State</label>-->
                                    <input type="text" class="form-control" id="CO_inputState" required placeholder="State/Province">
                                </div>
                                <div class="form-group col-md-2">
                                  <!--<label for="CO_inputZip">Zip</label>-->
                                  <input type="number" class="form-control" id="CO_inputZip" required placeholder="Zip">
                                </div>
                              </div>
                              <div class="form-group">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="CO_gridCheck">
                                  <label class="form-check-label" for="gridCheck" required>
                                    I Agree to <a href="#">Terms of Use</a>
                                  </label>
                                </div>
                              </div>
                              <div id="CO_updateProfileInfoReturned" class="col-12"></div>      
                              <button type="button" class="btn btn-primary w-100" @click="updateCO" id="CO_updateProfileButton">Update</button>
                            </form>
                            </div><!--end checkoutProfileInfo-->
                            <br>
                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <!------------**************End Modal CHECKOUT**************------------>
        
        <!------------**************Begin Modal CHECKOUT SUCCESS*********------------>
        <div class="modal fade" id="checkoutSuccessMain" tabindex="-1" role="dialog" aria-labelledby="checkoutSuccessMainTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutSuccessMainTitle">Success</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <div class="jumbotron jumbotron-fluid px-2 py-0 mt-3 mb-3">
                            <h2 class="display-5 text-center text-secondary font-weight-light">Thanks for your purchase <span id="buyerName"></span></h2>
                            <h5 class="alert alert-success text-center text-dark font-weight-light">Your Order Number is <span v-if="orderNumber > 0" class="mt-2 p-2 bg-light rounded border-top border-right">{{orderNumber}}</span><div v-else class="spinner-border spinner-border-sm text-secondary mx-auto" role="status"></div></h5>
                            <p class="mx-auto text-muted col-10 pb-3 text-center">You can track the progess of your order under your Account. Please don't hesitate to contact us if you have any queries.</p>
                            <div id="checkoutProfileInfo">
<?php
/*                                
  if(isset($_SESSION["username"])){
    $loggedInUser = $_SESSION["username"];
    }else{
      echo "<p>You must <a href=\"#\" data-toggle=\"modal\" data-target=\"#loginMain\" data-dismiss=\"modal\">log in</a> to set your delivery address</p>";
  }
*/  
?>                          </div><!--end checkoutProfileInfo-->  
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <!------------**************End Modal CHECKOUT SUCCESS*********------------>
        
        <!------------**************Begin Modal Logout**************------------>
        <div class="modal fade" id="logoutMain" tabindex="-1" role="dialog" aria-labelledby="logoutMainTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutMainTitle">Logout</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <h2 class="mx-auto col-10 text-center text-secondary font-weight-light" id="logOutInfo">logout</h2>
                    </div>
                </div>
            </div>
        </div>    
        <!------------**************End Modal logout**************------------>
        
        <!------------**************Begin Modal LOGIN**************------------>
        <div class="modal fade" id="loginMain" tabindex="-1" role="dialog" aria-labelledby="loginMainTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-muted" id="loginMainTitle">The Startup Store</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeLoginModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <!----------**********Login**********---------->
                        <div id="loginWrapper" class="show collapse multi-collapse">
                            
                            
                            <form class="form-signin">
                                <!--<h2 class="text-muted">Login</h2>-->
                                <p class="h3 mb-3 font-weight-light">Please Login Here</p>
                                <div class="form-group">
                                    <label for="username" class="sr-only">Username</label>
                                    
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                                    
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                    <span id="username_err"><!--return error here via php--></span>
                                    <span id="password_err"><!--return error here via php--></span>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-lg btn-primary btn-block" value="Login" onclick="loginUser()">Login</button>
                                </div>
                                <p>Forgot password <a href="#forgotWrapper" @click="forgotWrapper">Reset now</a>.</p>
                                <p>Don't have an account? <a href="#registerWrapper" @click="registerWrapper">Sign up now</a>.</p>
                            </form>
                        </div>
                        <!----------**********End Login**********---------->
                        <!----------**********Forgot**********---------->
                        <div class="collapse multi-collapse" id="forgotWrapper">
                            <h2>Reset Password</h2>
                            <p>Please confirm your email address to reset your password.</p>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <!--Email Address-->
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" id="forgotEmail" required>
                                    <span class="help-block alert-danger text-muted" id="forgot_err"></span>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" id="forgotButton" value="Send" @click="forgotUser">Send</button>
                                </div>
                                <p>Don't have an account? <a href="#registerWrapper" @click="registerWrapper">Sign up now</a>.</p>
                                <p>Already have an account? <!--<a data-toggle="collapse" data-target=".multi-collapse" href="#loginWrapper">--><a href="#loginWrapper" @click="loginWrapper">Login here</a>.</p>
                            </form>
                        </div>
                        <!----------**********End Forgot**********---------->
                        <!----------**********Register**********---------->
                        <div id="registerWrapper" class="hide collapse multi-collapse">
                            <h2>Sign Up</h2>
                            <p>Please fill this form to create an account.</p>
                            <form>
                                <!--add bootstrap has-error class to from group if needed-->
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" id="registerUsername" required>
                                    <span class="help-block alert-danger text-muted" id="reg_username_err"><!--username error--></span>
                                </div>

                                <!--Email Address-->
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" id="registerEmail" required>
                                    <span class="help-block alert-danger text-muted" id="reg_email_err"><!--email error--></span>
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" id="registerPassword" required>
                                    <span class="help-block alert-danger text-muted" id="reg_password_err"><!--password error--></span>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="confirm_password" class="form-control" id="registerConfirmPassword" required>
                                    <span class="help-block alert-danger text-muted" id="reg_confirm_password_err"><!--confirm password error--></span>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" value="Submit" @click="registerUser" id="regSubmitButton">Submit</button>
                                    <button type="reset" class="btn btn-default" value="Reset">Reset</button>
                                </div>
                                <p>Already have an account? <a href="#loginWrapper" @click="loginWrapper">Login here</a>.</p>
                            </form>
                        </div> 
                        <!----------**********End Register**********---------->
                    </div><!--modal body-->
                </div><!--modal content-->
            </div><!--modal dialog-->
        </div><!--modal-->    
        <!------------**************End Modal LOGIN**************------------>
        
        <!------------**************Begin Modal User Profile**************------------>
        <div class="modal fade" id="userProfile" tabindex="-1" role="dialog" aria-labelledby="userProfileTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userProfileTitle">Account Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <div id="userProfileModal">
                            <form>
                                <div class="row input-group list-group px-2">
                                    <ul class="input-group-prepend list-inline">
                                        <li class="list-inline-item bg-light border-right border-bottom col-4 pt-3 text-right">
                                            <label class="sr-only" for="UP_inputUsername">Username</label>
                                            <p class="mx-auto">Username</p>
                                        </li>
                                        <li class="list-inline-item border-left col-8 pt-3 mt-0">
                                            <p id="UP_inputUsername" ></p>    
                                        </li>
                                    </ul>
                                </div>
                                <div class="row input-group list-group px-2">
                                    <ul class="input-group-prepend list-inline">
                                        <li class="list-inline-item bg-light border-right col-4 pt-3 text-right">
                                            <label class="sr-only" for="UP_inputEmail">Email</label>
                                            <p class="mx-auto">Email</p>
                                        </li>
                                        <li class="list-inline-item border-left col-8 pt-3 mt-0">
                                            <p id="UP_inputEmail" ></p>    
                                        </li>
                                    </ul>
                                </div>
                                <p class= "col-12 mt-3" id="UP_inputConfirm"></p>
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <!--<label for="UP_inputFirstname" class="text-muted" >Name</label>-->
                                  <input type="text" class="form-control" id="UP_inputFirstname" required placeholder="Name">
                                </div>
                                <div class="form-group col-md-6">
                                  <!--<label for="UP_inputSurname" class="text-muted">Surname</label>-->
                                  <input type="text" class="form-control" id="UP_inputSurname" required placeholder="Surname">
                                </div>
                              </div>      
                              <div class="form-group">
                                <!--<label for="UP_inputAddress" class="text-muted">Apartment/Complex Number</label>-->
                                <input type="text" class="form-control" id="UP_inputAddress" placeholder="Apartment/Complex Number">
                              </div>
                              <div class="form-group">
                                <!--<label for="UP_inputAddress2" class="text-muted">Street Number, Suburb</label>-->
                                <input type="text" class="form-control" id="UP_inputAddress2" placeholder="Street Number, Suburb">
                              </div>
                              <div class="form-row">
                                <div class="form-group col-md-6">
                                  <!--<label for="UP_inputCity" class="text-muted">City</label>-->
                                  <input type="text" class="form-control" id="UP_inputCity" required placeholder="City">
                                </div>
                                <div class="form-group col-md-6">
                                  <!--<label for="UP_inputState" class="text-muted">State</label>-->
                                    <input type="text" class="form-control" id="UP_inputState" required placeholder="State/Province">
                                </div>
                                <div class="form-group col-md-6">
                                  <!--<label for="UP_inputZip" class="text-muted">Zip</label>-->
                                  <input type="number" class="form-control" id="UP_inputZip" required placeholder="Zip">
                                </div>
                              </div>
                              <!--<div class="form-group">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" id="UP_gridCheck">
                                  <label class="form-check-label" for="gridCheck" required>
                                    Agree to Terms of Use
                                  </label>
                                </div>
                              </div>-->
                              <div id="updateProfileInfoReturned" class="col-12"></div>      
                              <button type="button" class="btn btn-primary col-12" @click="updateProfile" id="updateProfileButton">Update</button>
                            </form>
                        </div><!---end #userProfileModal--->
                    </div><!--modal body-->    
                </div><!--modal content-->    
            </div><!--modal dialog-->    
        </div>
        <!------------**************End Modal User Profile****************------------>
        
        <!------------**************Begin Modal ORDERS**************------------>
        <div class="modal fade" id="ordersMain" tabindex="-1" role="dialog" aria-labelledby="ordersMainTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ordersMainTitle">Track your current and past orders</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="continueShopping">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div><!--modal header-->
                    <div class="modal-body">
                        <div class="jumbotron jumbotron-fluid px-2 py-0 mt-3">
                            <h2 class="display-5 text-center text-secondary font-weight-light">Orders</h2>
                            <!--START-->
                            <div id="accordion">
                            </div>
                            <!--ENDS-->
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <!------------**************End Modal ORDERS**************------------>
        
<!------------------------*************HEADER************------------------------------->
        <div class="jumbotron">
            <div class="container">
            <h1 class="display-4">The Starter Store</h1>
            <p class="lead sm-ml-2">Welcome to our online store<br> Please click a product to find out more about it, or you can add it to your cart.</p>
            <button type="button" onclick="shopNavButtonScroll()" class="btn btn-success ml-2">Start Shopping</button>
            </div>    
        </div>
<!------------------------*************ABOUT*************------------------------------->
        <section>
        <div id="aboutBkg" class="mb-4">
            <div id="aboutBkgGrad">
                <div id="aboutContent" class="container py-5">
                    <div class="row mx-4">
                        <h2 class="display-4 text-muted col-12">About Us</h2>
                    <p class="col-12 lead text-muted mx-0">
                        From our humble beginnings at a weekend market store in the Waterfront district of Cape Town to our award winning international online store.<br>
                        We have always put our customers needs at the forefront of our business and belive that this is the philosophy that drives our success.<br>
                        Enjoy your shopping experience and please don't hesitate to contact us if you have any queries.
                    </p>
                    </div>    
                </div>    
            </div>
        </div>
        </section>      
<!--------------------------**********SHOP PRODUCTS**********-------------------------->        
<section id="mainShop">  
    <div class="container-fluid">
        <h2 class="display-4 text-muted text-center" id="mainShopHeader">Shop</h2>
        <div class="row">
<?php                 
//Build products from db            
$sql = "SELECT * FROM onlineshop_products";
$result = $conn->query($sql);          
            
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()) {
        echo "\n\t\t<div class=\"col\">
              \n\t\t\t<div class=\"card mx-auto products fadeIn mt-2\" style=\"width: 18rem;\" id=\"". $row["id"] ."\">
              <div id=\"".$row["product"]."_short_desc\" class=\"card-header font-weight-light display-5 text-muted\">" . $row["short_desc"] . "</div>
                <button type=\"button\" onclick=\"productModal(this)\" class=\"prod-btn-hover btn btn-light card-img-top px-2\" style=\"height:240px; width:240px;margin:auto; background: url(" . $row["image"] . ")\" data-toggle=\"modal\" data-target=\"#productModal\" data-backdrop=\"static\" data-keyboard=\"false\" data-product=\"".$row["product"]."\" data-shortdesc=\"".$row["short_desc"]."\"></button>
                <br> 
                \n\t\t\t\t<div class=\"purchase card-body mt-0 pt-0\">
                 <h5 class=\"card-title text-muted px-2 py-2 font-weight-light text-center mb-0\"> <input hidden readonly value=\"" . $row["price"] . "\" id=\"". $row["product"] . "price" . "\"><span class=\"font-weight-light text-muted ml-3\">$" . number_format($row["price"],2) . "
                    </span> </h5>
                <ul class=\"list-group list-group-flush\">
                    <li class=\"list-group-item\">
                    <div class=\"input-group\">
                    <div class=\"input-group-prepend\">
                    <label for=\"" . $row["product"] . "quantity\" class=\"text-muted input-group-text\">Quantity :</label>
                    </div>
                    <input type=\"number\" id=\"" . $row["product"] . "quantity\" name=\"". $row["product"] ."quantity\" value=\"1\" min=\"1\" max=\"5\" class=\"text-muted form-control\">
                    </div>
                    </li>
                    <li class=\"list-group-item\">
                    <button class=\"btn btn-info col-12\" type=\"button\" name=\"addToCart\" @click=\"addToCart\" id=\"". $row["product"] ."\">Add to Cart</button>\n
                    </li>
                </ul></div><!--close purchase class-->
            \n\t\t\t</div><!--close card-->
            \n\t\t</div><!--end colsm-->\n";
        }
    }
//echo "</div></div><!--end BS container-->"            
?>  
        </div><!--end BS row-->
    </div><!--end BS container-->        
</section>
<!--------------------------********END SHOP PRODUCTS********-------------------------->               
<br>
</main><!-- /.container -->
    
<hr class="featurette-divider">
<footer class="container py-5">
      <div class="row">
        <div class="col-12 col-md">
            <small class="d-block mb-3 text-muted"><p>All rights reserved</p></small>
            <small class="d-block mb-3 text-muted"><p>Terms and Conditions</p></small>
            <small class="d-block mb-3 text-muted">&copy; <span id="copywriteDate"></span></small>
        </div>
        <div class="col-6 col-md">
          <h5>Address</h5>
          <ul class="list-unstyled text-small">
            <li><p class="text-muted m-0" >17 Biscuit Drive</p></li>
            <li><p class="text-muted m-0" >Constantia</p></li>
            <li><p class="text-muted m-0" >Cape Town</p></li>
            <li><p class="text-muted m-0" >Western Cape</p></li>
            <li><p class="text-muted m-0" >7701</p></li>
            <li><p class="text-muted m-0" >South Africa</p></li>
          </ul>
        </div>
        <div class="col-6 col-md">
          <h5>Call us</h5>
          <ul class="list-unstyled text-small">
            <li><p class="text-muted m-0">0786954430</p></li>
            <li><p class="text-muted m-0">Operating Hours</p></li>
            <li><p class="text-muted m-0">9am-5pm (Mon-Fri) </p></li>
            <li><p class="text-muted m-0">9:30am-12pm (Sat)</p></li>
          </ul>
        </div>
        <div class="col-6 col-md">
          <h5>Get Social</h5>
          <ul class="list-unstyled text-small">
            <li><img src="images/social/facebook.jpg" class="socialIcons" alt="facebook"><a class="text-muted" href="#">Facebook</a></li>
            <li><img src="images/social/instagram.jpg" class="socialIcons" alt="instagram"><a class="text-muted" href="#">Instagram</a></li>
            <li><img src="images/social/linkedin.jpg" class="socialIcons" alt="linkedin"><a class="text-muted" href="#">Linkedin</a></li>
            <li><img src="images/social/twitter.jpg" class="socialIcons" alt="twitter"><a class="text-muted" href="#">Twitter</a></li>
            <li><img src="images/social/youtube.jpg" class="socialIcons" alt="youtube"><a class="text-muted" href="#">Youtube</a></li>  
          </ul>
        </div>
        <div class="col-6 col-md">
          <h5>Quick Links</h5>
          <ul class="list-unstyled text-small">
            <li><a class="text-muted" href="#">Shop</a></li>
            <li><a class="text-muted" href="#">About</a></li>
            <li><a class="text-muted" href="#">Contact</a></li>
            <li><a class="text-muted" href="#">Login</a></li>
          </ul>
        </div>
      </div>
    </footer>    
    
</div><!--close shoppingApp-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster MUST be outside of Vue managed elements-->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!--<script src="https://unpkg.com/vue"></script>--><!--production vue-->
    <script src="scripts/vue.js"></script>
    <script src="scripts/onlineStore.js"></script>
    <script src="scripts/vueScripts.js"></script>
    <script
    src="https://www.paypal.com/sdk/js?client-id=XXXX"> // Required. Replace SB_CLIENT_ID with your sandbox client ID.
    </script>
    <script>
    paypal.Buttons({
    createOrder: function(data, actions) {
      // This function sets up the details of the transaction, including the amount and line item details.
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: shoppingCart.editCartNewPrice(shoppingCart.itemsObj)
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
        //alert('Transaction completed by ' + details.payer.name.given_name);
        shoppingCart.as_updateOrders(details);
        $("#checkoutSuccessMain").modal();
        document.getElementById("buyerName").innerHTML = details.payer.name.given_name;
        //document.getElementById("buyerName").innerHTML = details.id; 
        //console.log(details);  
        $("#checkoutMain").modal('hide');
        //shoppingCart.itemsObj = {};
        //shoppingCart.totalItems = 0;  
        //shoppingCart.upDateEditCart(shoppingCart.itemsObj);  
      }).then(function(){
            //Moved to vueScripts updateOrders
            //shoppingCart.itemsObj = {};
            //shoppingCart.totalItems = 0;  
            //shoppingCart.upDateEditCart(shoppingCart.itemsObj);
      });
    }
  }).render('#paypal-button-container');
  //This function displays Smart Payment Buttons on your web page.   
    </script>
<?php
            if(isset($loggedInUser)){
                //$loggedInUser = $_SESSION["username"];
                //echo $loggedInUser;
                $cartArr;
                $sql = "SELECT cart FROM onlineshop_activecart WHERE username = '$loggedInUser'";
                $result = $conn->query($sql);
                //var_dump($result);
                if($result->num_rows > 0){    
                    $row = $result->fetch_assoc();
                    $cartArr = $row["cart"];
                    //var_dump($cartArr);
                    //call to JS function with a Vue hook
                    
                    echo '<script>
                    let paramArr;
                    if(JSON.stringify('.$cartArr.') != "{}"){
                     paramArr = JSON.parse(JSON.stringify('.$cartArr.'));
                    }else{
                     paramArr = {};
                    }
                    ';
                    echo 'console.log(paramArr);
                    cleanUpVue(paramArr);
                    </script>';
                }     
            }else{
                echo "user not logged in";
            }
?>    
</body>
