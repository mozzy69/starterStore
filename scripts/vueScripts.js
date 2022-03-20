/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

let shoppingCart = new Vue({
                el : '#shoppingApp',
                data : {
                    //not using totalPrice
                    totalItems : 0,
                    itemsObj : {},
                    quantities : [],
                    totalPrice : 0,
                    priceArr : [],
                    orderNumber : 0,
                    delivery : {}
                }, 
                methods : {
                    //Main Adding Object to itemsObj
                    addToCart(){
                        let prodName = event.target.id;
                        let prodPriceID = prodName+"price";
                        let prodShortDescID = prodName+"_short_desc";
                        let prodPrice = document.getElementById(prodPriceID).value;
                        let prodShortDesc = document.getElementById(prodShortDescID).textContent;
                        console.log(prodShortDesc);
                        document.getElementById(prodName).innerHTML = "Update Cart";
                        let numberOfItems = parseInt(document.getElementById(prodName+"quantity").value);
                        //console.log(numberOfItems);
                        this.updateItemsObj(this.itemsObj, prodName, numberOfItems);
                        //add price to itemsObj.product
                        this.itemsObj[prodName].price = prodPrice;
                        this.itemsObj[prodName].short_desc = prodShortDesc;
                        //forceUpdate not needed because Vue.set is used to add a reactive property
                        //this.$forceUpdate();
                        this.calcTotalPrice(this.itemsObj);
                        this.priceArr = [];
                        this.updateCartAjax();
                    },
                    //Updating quantities and and total price ONLY
                    updateItemsObj(obj, elem, item){
                        //obj[elem] = item;
                        //Must use Vue.set when adding a reactive property to an object
                        //Vue.set(object, key, value)
                        let itemObj = {};
                        //console.log(obj[elem].options);
                        if(typeof obj[elem] != "undefined"){
                            itemObj = obj[elem];
                        }else{
                            itemObj = {};
                        }
                        Vue.set( itemObj, 'quantity', item);
                        //Vue.set( itemObj, 'options', this.obj.elem.options);
                        Vue.set( obj, elem, itemObj );
                        this.totalItems = this.addAllCartItems(obj);
                    },
                    //Helper function for updateItemsObj to set total price
                    //This function is erroneous NOT USED for Total Price in NAV
                    addAllCartItems(cartItems){
                        var x = 0;
                        var total = 0;
                        for (x in cartItems) {
                           //total += cartItems[x];
                           //console.log(cartItems[x].quantity);
                             total += Number(cartItems[x].quantity);
                        }
                        return total;
                    },
                    deleteItem(){
                        //returns property Boxers, Dress etc
                        let x = event.target.value;
                        //console.log(x);
                        this.updateItemsObj(this.itemsObj, x, 0);
                        this.calcTotalPrice(this.itemsObj);
                        this.priceArr = [];
                    },
                    duplicateObj(obj){
                        for (x in obj){
                            //console.log(obj[x].quantity);
                            this.quantities.push(obj[x].quantity);
                            this.$forceUpdate();
                        }
                    },
                    //Calculate line items in cart
                    editCartLineItems(item1, item2){
                        return parseFloat(item1)*parseFloat(item2);
                    },
                    //calculate price of edited cart
                    editCartNewPrice(obj){
                        let y;
                        let z = 0;
                        for (x in obj){
                            y = this.editCartLineItems(obj[x].quantity, obj[x].price);
                            z+=y;
                        }
                        //return this.toUSD(z);
                        return parseFloat(z);
                    },
                    //removed cancel functionallity
                    resetChanges(arr, obj){
                        let x = 0;
                        for(y in obj){
                            Vue.set( obj[y], 'quantity', arr[x]);
                            x++;
                            }
                        this.continueShopping();
                        this.totalItems = this.addAllCartItems(obj);
                        this.calcTotalPrice(obj);
                        return this.quantities;
                    },
                    calcTotalPrice(obj){
                        for (x in obj){
                            let y = x + "price";
                            let price = document.getElementById(y).value;
                            let itemTotal = Number(price) * Number(obj[x].quantity);
                            this.priceArr.push(itemTotal);
                            this.totalPrice = this.priceArr.reduce((a, b) => a + b);
                        }
                    },
                    calcTotalPriceCleanUp(obj,paramArr){
                        for (x in obj){
                            let y = x + "price";
                            let price = document.getElementById(y).value;
                            let itemTotal = Number(price) * Number(obj[x].quantity);
                            this.priceArr.push(itemTotal);
                            this.totalPrice = this.priceArr.reduce((a, b) => a + b);
                        }
                    },
                    //Set input spinners on products after EditCart
                    setInputSpinners(obj){
                        for(x in obj){
                            document.getElementById(x + "quantity").value = obj[x].quantity;
                        }
                    },
                    //This is the main function for updating the cart NOT updateCartAjax
                    upDateEditCart(obj){
                        this.calcTotalPrice(obj);
                        this.priceArr = [];
                        this.totalItems = this.addAllCartItems(obj);
                        this.setInputSpinners(obj);
                        this.continueShopping();
                        this.updateCartAjax();
                        //document.getElementById("Boxersquantity").value = 5;
                    },
                    continueShopping(){
                        this.quantities = [];
                        this.priceArr = [];
                        this.$forceUpdate();
                    },
                    //next x4 functions fix modal and collapse behaviour for login/signup
                    //this is Bootstrap 4 specific using jQuery
                    closeLoginModal(){
                      //used to reset login modal to always start with login screen as default
                        $("#loginWrapper").collapse('show');
                        $("#registerWrapper").collapse('hide');  
                    },
                    loginWrapper(){
                      //used to reset login modal to always start with login screen as default
                        $("#loginWrapper").collapse('show');
                        $("#registerWrapper").collapse('hide');
                        $("#forgotWrapper").collapse('hide');
                    },
                    forgotWrapper(){
                      //used to reset login modal to always start with login screen as default
                        $("#loginWrapper").collapse('hide');
                        $("#registerWrapper").collapse('hide');
                        $("#forgotWrapper").collapse('show');
                    },
                    registerWrapper(){
                      //used to reset login modal to always start with login screen as default
                        $("#loginWrapper").collapse('hide');
                        $("#registerWrapper").collapse('show');
                        $("#forgotWrapper").collapse('hide');
                    },
                    emptyOptions(){
                        document.getElementById("prodOptionsCheckbox").innerHTML = "";
                        },
                    cleanup(paramArr){
                        console.log("we are cleaning up");
                        //incomplete ...ok now ...test more
                        /*
                        for(i = 0; i < paramArr.length; i+=2){
                            this.updateItemsObj(this.itemsObj, paramArr[i], paramArr[i+1]);
                            //console.log(paramArr[0]);
                        } 
                        */
                        //set PHP paramArr see index to vue object
                        this.itemsObj = paramArr;
                        //calc price
                        this.calcTotalPriceCleanUp(this.itemsObj, paramArr);
                        //calc number of items
                        this.totalItems = this.addAllCartItems(paramArr);
                        //update input spinners on products
                        this.upDateEditCart(paramArr);
                        //change button to update cart
                        for(x in paramArr){
                            document.getElementById(x).innerHTML = "Update Cart";
                            //console.log(x);
                        }
                        this.priceArr = [];
                    },
                    toUSD(paramPrice){
                        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(paramPrice); //18.43;
                    },
                    //update onlineshop_activecart DB from addToCart, upDateAddToCart, getCheckedBoxes
                    //see updateEditCart for main update cart function
                    updateCartAjax(){
                        let allItems = JSON.stringify(this.itemsObj);
                        //console.log(allItems);
                        var xhr = new XMLHttpRequest();
                        // Track the state changes of the request.
                        xhr.onreadystatechange = function () {
                            const DONE = 4; // readyState 4 means the request is done.
                            const OK = 200; // status 200 is a successful return.
                            if (xhr.readyState === DONE) {
                                if (xhr.status === OK) {
                                    console.log(xhr.responseText); // 'This is the output.'
                                    //console.log(xhr.readyState);
                                } else {
                                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                                }
                            }
                        };
                    // Send the request to send-ajax-data.php
                    //xhr.open("GET", "./incl/cart.php?q="+allItems, true);
                    //xhr.send();
                    xhr.open("POST", "./incl/cart.php", true);
                    xhr.send(allItems);    
                    },
                    //if users cart is empty replaces with active cart
                    replaceEmptyCart(){
                        //let allItems = JSON.stringify(this.itemsObj);
                        const comp = this;
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            const DONE = 4; 
                            const OK = 200;
                            if (xhr.readyState === DONE) {
                                if (xhr.status === OK) {
                                    console.log(xhr.responseText); // 'This is the output.'
                                    //console.log(xhr.readyState);
                                    let cartObj = JSON.parse(xhr.responseText);
                                    console.log(cartObj);
                                    comp.itemsObj = cartObj;
                                    if((Object.keys(cartObj).length === 0 && cartObj.constructor === Object) || comp.addAllCartItems(comp.itemsObj) == 0) {
                                        console.log("cart is empty");
                                    }else{
                                        comp.upDateEditCart();
                                        comp.totalItems = comp.addAllCartItems(comp.itemsObj);
                                    }
                                } else {
                                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                                }
                            }
                        };
                    // Send the request to send-ajax-data.php
                    xhr.open("GET", "./incl/replaceEmptyCart.php", true);
                    xhr.send();
                    },
                    forgotUser(){
                        document.getElementById("forgotButton").innerHTML =  "<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"pl-2\">Processing...</span>";
                        if(document.getElementById("forgot_err").innerHTML !== ""){
                            document.getElementById("forgot_err").innerHTML = "";
                        }
                        let forgotUser_E = document.getElementById("forgotEmail").value;
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "./incl/forgot.php", true); 
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onreadystatechange = function() {
                           if (this.readyState == 4 && this.status == 200) {
                             // Response
                             var response = JSON.parse(this.responseText);
                               //console.log(response);
                               if(response.forgot_err === ""){
                                  document.getElementById("forgotWrapper").innerHTML = response.forgot_msg; 
                               }else{
                                   document.getElementById("forgot_err").innerHTML = response.forgot_err;
                                   document.getElementById("forgotButton").innerHTML = "Send";
                               }
                               //document.getElementById("password_err").innerHTML = response.password_err;
                               //console.log(this.responseText);
                           }
                        };
                        var data = {email:forgotUser_E};
                        xhr.send(JSON.stringify(data));
                    },
                    registerUser(){
                        var comp = this;
                        document.getElementById("regSubmitButton").innerHTML = "<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"pl-2\">Processing...</span>";
                        let registerUser_UN = document.getElementById("registerUsername").value;
                        let registerUser_E = document.getElementById("registerEmail").value;
                        let registerUser_PW = document.getElementById("registerPassword").value;
                        let registerUser_PWC = document.getElementById("registerConfirmPassword").value;
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                           if (this.readyState == 4 && this.status == 200) {
                             // Response 
                               console.log(this.readyState);
                               var response = JSON.parse(this.responseText);
                               console.log(response);
                               document.getElementById("reg_username_err").innerHTML = response.username_err;
                               document.getElementById("reg_email_err").innerHTML = response.email_err;
                               document.getElementById("reg_password_err").innerHTML = response.password_err;
                               document.getElementById("reg_confirm_password_err").innerHTML = response.confirm_password_err;
                               document.getElementById("regSubmitButton").innerHTML = "<span class=\"pl-2\">Retry</span>";
                               if(response.username_err=="" && response.email_err=="" && response.password_err=="" && response.confirm_password_err==""){
                                   document.getElementById("registerWrapper").innerHTML = response.username_info;
                                   comp.updateCartAjax();  
                               };
                           }else{
                               console.log(this.readyState);
                               console.log(this.responseText);
                           }
                        };
                        var data = {username:registerUser_UN, 
                                    email:registerUser_E, 
                                    password:registerUser_PW, 
                                    confirmPass:registerUser_PWC};
                        xhr.open("POST", "./incl/register.php", true); 
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.send(JSON.stringify(data));
                    },
                    //update user profile
                    updateProfile(){
                        var comp = this;
                        document.getElementById("updateProfileButton").innerHTML = "<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"pl-2\">Processing...</span>";
                        let username_udp = document.getElementById("UP_inputUsername").innerHTML;
                        let email_udp = document.getElementById("UP_inputEmail").innerHTML;
                        let firstname_udp = document.getElementById("UP_inputFirstname").value;
                        let surname_udp = document.getElementById("UP_inputSurname").value;
                        let address1_udp = document.getElementById("UP_inputAddress").value;
                        let address2_udp = document.getElementById("UP_inputAddress2").value;
                        let city_udp = document.getElementById("UP_inputCity").value;
                        let state_udp = document.getElementById("UP_inputState").value;
                        let zip_udp = document.getElementById("UP_inputZip").value;
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "./incl/updateProfile.php", true); 
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onreadystatechange = function() {
                           if (this.readyState == 4 && this.status == 200) {
                             // Response
                             var response = this.responseText;
                               //console.log(response);
                             document.getElementById("updateProfileInfoReturned").innerHTML = "<p class=\"alert alert-success alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>" + response + "</p>";
                             document.getElementById("updateProfileButton").innerHTML = "Update";   
                           }
                        };
                        var data = {username : username_udp,
                                    email : email_udp,
                                    firstname:firstname_udp, 
                                    surname:surname_udp, 
                                    address:{
                                        address1 : address1_udp,
                                        address2 : address2_udp,
                                        city     : city_udp,
                                        state    : state_udp,
                                        zip      : zip_udp
                                        }
                                   };
                        xhr.send(JSON.stringify(data));
                    },
                    checkout(){
                        //console.log("checkin out");
                        var comp = this;
                        let email_co = document.getElementById("CO_inputEmail").value;
                        let firstname_co = document.getElementById("CO_inputFirstname").value;
                        let surname_co = document.getElementById("CO_inputSurname").value;
                        let address1_co = document.getElementById("CO_inputAddress").value;
                        let address2_co = document.getElementById("CO_inputAddress2").value;
                        let city_co = document.getElementById("CO_inputCity").value;
                        let state_co = document.getElementById("CO_inputState").value;
                        let zip_co = document.getElementById("CO_inputZip").value;
                        var xhr = new XMLHttpRequest();
                        xhr.open("GET", "./incl/profiles.php", true); 
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                // Response
                                var response = this.responseText;
                                let responseObj = JSON.parse(response);
                                let responseAddress = {};
                                if(responseObj.address !== null){
                                    responseAddress = JSON.parse(responseObj.address);
                                    //console.log(responseAddress);
                                }
                                if(Object.keys(comp.delivery).length === 0){
                                    document.getElementById("CO_inputEmail").value = responseObj.email;
                                    document.getElementById("CO_inputFirstname").value = responseObj.firstname;
                                    document.getElementById("CO_inputSurname").value = responseObj.surname;
                                    document.getElementById("CO_inputAddress").value = responseAddress.address1;
                                    document.getElementById("CO_inputAddress2").value = responseAddress.address2;
                                    document.getElementById("CO_inputCity").value = responseAddress.city;
                                    document.getElementById("CO_inputState").value = responseAddress.state;
                                    document.getElementById("CO_inputZip").value = responseAddress.zip;    
                                }else{
                                    document.getElementById("CO_inputEmail").value = comp.delivery.email;
                                    document.getElementById("CO_inputFirstname").value = comp.delivery.firstname;
                                    document.getElementById("CO_inputSurname").value = comp.delivery.surname;
                                    document.getElementById("CO_inputAddress").value = comp.delivery.address1;
                                    document.getElementById("CO_inputAddress2").value = comp.delivery.address2;
                                    document.getElementById("CO_inputCity").value = comp.delivery.city;
                                    document.getElementById("CO_inputState").value = comp.delivery.state;
                                    document.getElementById("CO_inputZip").value = comp.delivery.zip;
                                }
                                comp.updateCO();
                            }
                        };
                        xhr.send();
                    },
                    updateCO(){
                        document.getElementById("CO_updateProfileButton").innerHTML = "<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span><span class=\"pl-2\">Calculating...</span>";
                        this.delivery = Object.assign({}, this.address, { 
                            email: document.getElementById("CO_inputEmail").value, 
                            firstname: document.getElementById("CO_inputFirstname").value, 
                            surname: document.getElementById("CO_inputSurname").value,
                            address1 : document.getElementById("CO_inputAddress").value,
                            address2 : document.getElementById("CO_inputAddress2").value,
                            city : document.getElementById("CO_inputCity").value,
                            state : document.getElementById("CO_inputState").value,
                            zip : document.getElementById("CO_inputZip").value
                        });
                        document.getElementById("CO_updateProfileButton").innerHTML = "Update";
                        //$('#paypal-button-container').collapse('show');
                    },
                    //update orders
                    updateOrders(paramDetails){
                        let comp = this;
                        let orderDate = this.orderDate();
                        //console.log(orderDate);
                        let orderStatus = "Processing";
                        let orderDetails = JSON.stringify(paramDetails);
                        //console.log(orderDetails);
                        //let allItems = JSON.stringify(this.itemsObj);
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            const DONE = 4; // readyState 4 means the request is done.
                            const OK = 200; // status 200 is a successful return.
                            if (xhr.readyState === DONE) {
                                if (xhr.status === OK) {
                                    var responseObj = JSON.parse(xhr.responseText);
                                    console.log(responseObj); // 'This is the output.'
                                    var orderedCart = JSON.parse(responseObj.cart);
                                    //console.log(orderedCart);
                                    //console.log(comp.takeStock(orderedCart));
                                    comp.removeStockDB(comp.takeStock(orderedCart));
                                    comp.orderNumber = responseObj.ordernumber;
                                    
                                    //Reset Cart
                                    comp.itemsObj = {};
                                    comp.totalItems = 0;  
                                    comp.upDateEditCart(comp.itemsObj);
                                } else {
                                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                                }
                            }
                        };
                    // Send the request to send-ajax-data.php
                    xhr.open("POST", "./incl/updateOrders.php", true);
                    let orderObj = {
                        orderDate : orderDate,
                        status : orderStatus,
                        details : orderDetails,
                        delivery : JSON.stringify(this.delivery),
                        grandTotal : this.editCartNewPrice(this.itemsObj)
                    };
                    //let data = JSON.stringify(orderObj);    
                    xhr.send(JSON.stringify(orderObj));
                    },
                    async as_updateOrders(as_paramDetails){
                       await this.updateOrders(as_paramDetails); 
                    },
                    //get date
                    orderDate(){
                        var MyDate = new Date();
                        var MyDateString;
                        MyDateString =  MyDate.getFullYear() + 
                                        ('0'+ (MyDate.getMonth()+1)).slice(-2) +
                                        ('0' + MyDate.getDate()).slice(-2);
                        return MyDateString;
                    },
                    //build quantities object for subtracting stock
                    takeStock(cartObj){
                        var removeStockObj = {};
                        for(x in cartObj){
                            //console.log(cartObj[x].quantity);
                            if (parseInt(cartObj[x].quantity) > 0 ){
                                removeStockObj[x] = cartObj[x].quantity;
                            }
                        }
                        return removeStockObj;
                    },
                    //helper function for updateOrders to remove stock from products_DB
                    removeStockDB(stockObj){
                        //let stockData = JSON.stringify(stockObj);
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            const DONE = 4; 
                            const OK = 200; 
                            if (xhr.readyState === DONE) {
                                if (xhr.status === OK) {
                                    //var responseObj = JSON.parse(xhr.responseText);
                                    console.log(xhr.responseText);
                                } else {
                                    console.log('Error: ' + xhr.status); 
                                }
                            }
                        };
                    // Send the request to send-ajax-data.php
                    xhr.open("POST", "./incl/takeStock.php", true);
                    //let data = JSON.stringify(orderObj);    
                    xhr.send(JSON.stringify(stockObj));
                    }
                },//end methods
                computed : {
                    calcCartItems(){
                        return 0;
                    }
                }
            });