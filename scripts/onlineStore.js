/********************************

Copyright (c) 2020 Lyndon Daniels

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*********************************/

//Scroll to About section with Jquery
let aboutNavButtonScroll = ()=>{
    $("html, body").animate({
        scrollTop: $("#aboutContent").offset().top }, 500);
}
let homeNavButtonScroll = ()=>{
    $("html, body").animate({
        scrollTop: $("body").offset().top }, 500);
}
let shopNavButtonScroll = ()=>{
    $("html, body").animate({
        scrollTop: $("#mainShop").offset().top }, 500);
}

//fade products in on scroll definition
const isElemVisable = (elem, className)=> 
{
    $(elem).each(function(){
            //returns elements Y pos offset from document window
            let elementBottom = $(this).offset().top + $(this).outerHeight()/8;
            let windowBottom = $(window).scrollTop() + $(window).height();
            if( windowBottom > elementBottom ){
                $(this).addClass(className);
            }
        });    
}

//fade products in on scroll call
$(document).ready(function() {
    isElemVisable('.fadeIn', 'isVisible');
    //will execute everytime user scrolls
    $(window).scroll( function(){
       isElemVisable('.fadeIn', 'isVisible'); 
    });//end scroll functionallity
});//end document.ready


//this should be a promise
let productModal = (elem)=>{
    let prodID = elem.parentNode.id;
    let prodTitle = elem.getAttribute("data-product");
    let prodSecTitle = elem.getAttribute("data-shortdesc");
    //shoppingCart.productModalShortDesc = prodSecTitle;
    //the array of options the user has selected
    let prodOptions =[];
    //document.getElementById("prodOptionsCheckbox").innerHTML = "";
    var xhr = new XMLHttpRequest();    
        // Track the state changes of the request.
        xhr.onreadystatechange = function () {
            const DONE = 4; // readyState 4 means the request is done.
            const OK = 200; // status 200 is a successful return.
            if (xhr.readyState === DONE) {
                if (xhr.status === OK) {
                    let prodObj = JSON.parse(xhr.responseText);
                    console.log(prodObj);
                    //Build the array of options user has selected
                    //for the currently selected product
                    if(prodObj.hasOwnProperty("cart")){
                        let mainCart = JSON.parse(prodObj.cart);
                        console.log(mainCart);
                        //check if the cart object is empty
                        if(Object.keys(mainCart).length !== 0 && mainCart.constructor === Object){
                            //let prodOptions = mainCart[prodTitle];
                            if(mainCart.hasOwnProperty(prodTitle)){
                                if(mainCart[prodTitle].hasOwnProperty("options")){
                                    prodOptions = mainCart[prodTitle].options;
                                    console.log(prodOptions);
                                }
                            }
                        }
                    }   
                    let prodGallery = prodObj.gallery;
                    console.log("prodGallery : "+prodGallery);
                    let prodGalArr;
                    if(prodGallery === null){
                        prodGalArr = [];
                    }else{
                        prodGalArr = prodGallery.split(",");
                    };
                    let prodMainImage = prodObj.image;
                    let prodOpt = prodObj.options_checkbox;
                    let prodOptArr;
                    if(prodOpt === null){
                        prodOptArr = [];
                        document.getElementById("productsModalButton").innerHTML = "Close";
                    }else{
                        prodOptArr = prodOpt.split(",");
                        document.getElementById("productsModalButton").innerHTML = "Update";
                        document.getElementById("productsModalButton").setAttribute("data-product", prodObj.product);
                        document.getElementById("productsModalButton").setAttribute("data-price", prodObj.price);
                        document.getElementById("productsModalButton").setAttribute("data-short_desc", prodObj.short_desc);
                    };
                    let prodPrice = prodObj.price;
                    let cara;
                    
                    //Carasol
                    if(prodGalArr.length > 0){
                        let cara1= "<div id=\"carouselExampleControls\" class=\"carousel slide\" data-ride=\"carousel\"><ul class=\"carousel-indicators\"><li data-target=\"#carouselExampleControls\" data-slide-to=\"0\" class=\"active\"></li>";
                        let cara2 = cara1;
                        for(x in prodGalArr){
                            let z = parseInt(x)+1;
                            cara2 += "<li data-target=\"#carouselExampleControls\" data-slide-to=\""+z+"\"></li>";
                        }
                        let cara3="</ul><div class=\"carousel-inner\"><div class=\"carousel-item active\"><img class=\"d-block w-100\" src=\" "+prodMainImage+" \" alt=\"First slide\"></div>";
                        let cara4 = cara3;
                        for(x in prodGalArr){
                            cara4 += "<div class=\"carousel-item\"><img class=\"d-block w-100\" src=\" "+prodGalArr[x]+" \" ></div>";
                        }
                        let cara5 = "</div><a class=\"carousel-control-prev\" href=\"#carouselExampleControls\" role=\"button\" data-slide=\"prev\"><span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span><span class=\"sr-only\">Previous</span></a><a class=\"carousel-control-next\" href=\"#carouselExampleControls\" role=\"button\" data-slide=\"next\"><span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span><span class=\"sr-only\">Next</span></a></div>";
                        cara =  cara2 + cara4 + cara5;
                    }else{
                        cara = "<div id=\"carouselExampleControls\" class=\"carousel slide\" data-ride=\"carousel\"><ul class=\"carousel-indicators\"><li data-target=\"#carouselExampleControls\" data-slide-to=\"0\" class=\"active\"></li>    </ul><div class=\"carousel-inner\"><div class=\"carousel-item active\">            <img class=\"d-block w-100\" src=\" "+prodMainImage+" \" alt=\"First slide\">  </div></div><a class=\"carousel-control-prev\" href=\"#carouselExampleControls\" role=\"button\" data-slide=\"prev\"><span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span><span class=\"sr-only\">Previous</span></a><a class=\"carousel-control-next\" href=\"#carouselExampleControls\" role=\"button\" data-slide=\"next\"><span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span><span class=\"sr-only\">Next</span></a></div>";
                    }
                    //Options Checkbox
                    let goodToPrint;
                    for (i = 0; i < prodOptArr.length; i++) {
                      if(i === 0){
                          document.getElementById("prodOptionsCheckboxTitle").innerHTML = prodOptArr[i];
                      }else{
                          goodToPrint = true;
                          //TODO - run ajax call from here to onlineshop_active cart to determine which items are checked and which are unchecked
                          for(n = 0; n < prodOptions.length; n++){
                              console.log(prodOptions[n]);
                              console.log(prodOptArr[i]);
                              if(prodOptions[n] == prodOptArr[i]){
                                  goodToPrint = false;
                                  document.getElementById("prodOptionsCheckbox").innerHTML += 
                              "<input type=\"radio\" id=\"option" +i+ "\" name=\""+prodTitle+"_productCheckboxes\" value=\""+prodOptArr[i]+"\" checked> <label for=\"option"+i+"\">" +prodOptArr[i]+ "</label><br>";
                              }
                          }
                          if(goodToPrint){
                              document.getElementById("prodOptionsCheckbox").innerHTML += 
                              "<input type=\"radio\" id=\"option" +i+ "\" name=\""+prodTitle+"_productCheckboxes\" value=\""+prodOptArr[i]+"\"> <label for=\"option"+i+"\">" +prodOptArr[i]+ "</label><br>";
                          }
                          
                      }
                    }
                    
                    //not using this?
                    //let checkboxTitle = prodTitle+"_productCheckboxes";
                    //let prodModalFooterButtons = "<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" onclick=\"emptyOptions()\">Close</button><button type=\"button\" class=\"btn btn-primary\" @click=\"getCheckedBoxes("+checkboxTitle+")\">Save changes</button>";
                    
                    //Product Modal
                    document.getElementById("modalProductDetails").innerHTML = cara;
                    //document.getElementById("modalLongTitle").innerHTML = prodTitle;
                    document.getElementById("modalProductPrice").innerHTML = shoppingCart.toUSD(prodPrice);
                    document.getElementById("prodSecTitle").innerHTML = prodObj.short_desc;
                    //let productModalName = prodObj.product;
                    //console.log(productModalName);
                    //console.log(shoppingCart.itemsObj.productModalName);
                    document.getElementById("prodLongDesc").innerHTML = prodObj.long_desc;
                    //document.getElementById("prodModalFooterButtons").innerHTML = prodModalFooterButtons;
                } else {
                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                }
            }
        };
        // Send the request to send-ajax-data.php
        xhr.open("GET", "http://class/onlineStore/incl/productModal.php?q="+prodID, true);
        xhr.send();    
} 

let loginUser = ()=>{
                        //let comp = this;
                        let loginUser_UN = document.getElementById("username").value;
                        let loginUser_PW = document.getElementById("password").value;
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "./incl/login.php", true); 
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.onreadystatechange = function() {
                           if (this.readyState == 4 && this.status == 200) {
                             // Response
                             var response = JSON.parse(this.responseText);
                               //console.log(response);
                               if(response.username_err === "" && response.password_err ===""){
                                   document.getElementById("accountLoginOut").innerHTML = "<li class=\"nav-item dropdown\"><a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"dropdown01\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">Account</a><div class=\"dropdown-menu\" aria-labelledby=\"dropdown01\"><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#userProfile\" onclick=\"userProfiles()\">Profile</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#ordersMain\" onclick=\"userOrders()\">Orders</a><a class=\"dropdown-item\" href=\"#\" data-toggle=\"modal\" data-target=\"#logoutMain\" onclick=\"logoutUser()\">Log out</a></div></li>";
                                   //Bootstrap 4 specific to close login modal
                                   $("#loginMain").modal('hide');
                                   if(Object.keys((shoppingCart.itemsObj).length === 0 && shoppingCart.itemsObj.constructor === Object) || shoppingCart.totalItems == 0){
                                     console.log("cart is empty");
                                     shoppingCart.replaceEmptyCart();
                                     shoppingCart.totalItems = shoppingCart.addAllCartItems(shoppingCart.itemsObj);   
                                    }else{
                                     shoppingCart.updateCartAjax();    
                                    }
                               }else{
                                   if(response.username_err != ""){
                                    document.getElementById("username_err").innerHTML = "<p class=\"alert alert-danger alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>" + response.username_err + "</p>";     
                                   }
                                   if(response.password_err != ""){
                                    document.getElementById("password_err").innerHTML = "<p class=\"alert alert-danger alert-dismissible\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>" + response.password_err + "</p>";   
                                   }   
                               }
                           }
                        };
                        var data = {username:loginUser_UN,password: loginUser_PW};
                        xhr.send(JSON.stringify(data));
                    }

let copywriteDate = ()=>{
    var d = new Date();
    var n = d.getFullYear();
    document.getElementById("copywriteDate").innerHTML = n;
}
copywriteDate();

//gets user profile data from db
let userProfiles = ()=>{
                        //let allItems = JSON.stringify(this.itemsObj);
                        var xhr = new XMLHttpRequest();
                        // Track the state changes of the request.
                        xhr.onreadystatechange = function () {
                            const DONE = 4; // readyState 4 means the request is done.
                            const OK = 200; // status 200 is a successful return.
                            if (xhr.readyState === DONE) {
                                if (xhr.status === OK) {
                                    //console.log(JSON.parse(xhr.responseText)); // 'This is the output.'
                                    var response = JSON.parse(xhr.responseText);
                                    //console.log(response);
                                    document.getElementById("UP_inputEmail").innerHTML = response.email;
                                    document.getElementById("UP_inputUsername").innerHTML = response.username;
                                    //console.log(response.address);
                                    if(JSON.parse(response.address) != null){
                                        let mainAddress = JSON.parse(response.address);
                                        document.getElementById("UP_inputAddress").value = mainAddress.address1;
                                        document.getElementById("UP_inputAddress2").value = mainAddress.address2;
                                        document.getElementById("UP_inputCity").value = mainAddress.city;
                                        document.getElementById("UP_inputState").value = mainAddress.state;
                                        document.getElementById("UP_inputZip").value = mainAddress.zip;
                                        //console.log(mainAddress);
                                    }                                
                                    document.getElementById("UP_inputFirstname").value = response.firstname;
                                    document.getElementById("UP_inputSurname").value = response.surname;
                                    
                                    if(response.confirm == 1){
                                        document.getElementById("UP_inputConfirm").innerHTML = "<p class=\"alert alert-success alert-dismissible text-center\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Thanks for confirming your email address.</p>";
                                    }else{
                                        document.getElementById("UP_inputConfirm").innerHTML = "<p class=\"alert alert-danger text-center\">Please check your emails to confirm this email address.</p>"
                                    }
                                    //console.log(xhr.readyState);
                                } else {
                                    console.log('Error: ' + xhr.status); // An error occurred during the request.
                                }
                            }
                        };
                    // Send the request to send-ajax-data.php
                    xhr.open("GET", "./incl/profiles.php", true);
                    xhr.send();
                    }

let logoutUser = ()=>{
                        var xhr = new XMLHttpRequest(); 
                        xhr.onreadystatechange = function() {
                           if (this.readyState == 4 && this.status == 200) {
                             // Response
                             var response = this.responseText;
                               //console.log(response);
                               document.getElementById("logOutInfo").innerHTML = response;
                               document.getElementById("accountLoginOut").innerHTML = "<li class=\"nav-item\"><a class=\"nav-link\" href=\"#\" data-toggle=\"modal\" data-target=\"#loginMain\" data-dismiss=\"modal\">Log in</a></li>";
                           }
                        };
                        xhr.open("POST", "./incl/logout.php", true);
                        xhr.setRequestHeader("Content-Type", "application/json");
                        xhr.send();
                    }

let userOrders = ()=>{
    var buildOrders = "";
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        const DONE = 4; // readyState 4 means the request is done.
        const OK = 200; // status 200 is a successful return.
        if (xhr.readyState === DONE) {
            if (xhr.status === OK) {
                var response = JSON.parse(xhr.responseText);
                //console.log(response);
                for(x in response){
                    buildOrders += "<div class=\"card\"><button class=\"card-header btn btn-link\" id=\"heading"+x+"\" data-toggle=\"collapse\" data-target=\"#collapse"+x+"\" aria-expanded=\"true\" aria-controls=\"collapse"+x+"\"><h5 class=\"mb-0\">          Order "+(parseInt(x)+1)+"</h5></button>";
                    
                    buildOrders += "<div id=\"collapse"+x+"\" class=\"collapse show\" aria-labelledby=\"heading"+x+"\" data-parent=\"#accordion\">";
                    
                    buildOrders += "<div class=\"card-body\"><h4 class=\"text-muted\">Transaction #"+response[x][0]+"</h4><h5 class=\"text-muted\">Date and Time: "+response[x][2]+" </h5>";
                    
                    switch(response[x][1]) {
                        case "Packaging":
                            buildOrders += "<div class=\"progress\"><div class=\"progress-bar bg-warning\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"25\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div><div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"50\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>";
                            break;
                        case "Shipped":
                            buildOrders += "<div class=\"progress\"><div class=\"progress-bar bg-warning\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"25\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div><div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"50\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div><div class=\"progress-bar bg-primary\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"75\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>";
                            break;
                        case "Delivered":
                            buildOrders += "<div class=\"progress\"><div class=\"progress-bar bg-warning\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"25\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div><div class=\"progress-bar bg-info\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"50\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div><div class=\"progress-bar bg-primary\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"75\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>        <div class=\"progress-bar bg-success\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>";
                            break;    
                        default:
                            buildOrders += "<div class=\"progress\"><div class=\"progress-bar bg-warning\" role=\"progressbar\" style=\"width: 25%\" aria-valuenow=\"25\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div></div>";
                            break;
                    }
                    buildOrders += "<div class=\"row\"><ul class=\"col-12\"><li class=\"orders_li col-3 text-muted font-weight-light border-left mx-auto\">Processing</li>                <li class=\"orders_li col-3 text-muted font-weight-light border-left mx-auto\">Packaging</li><li class=\"orders_li col-3 text-muted font-weight-light border-left mx-auto\">Shipped</li><li class=\"orders_li col-3 text-muted font-weight-light border-left border-right mx-auto\">Delivered</li></ul></div>      </div><!--cardbody--></div><!--collapseX-->";
                    //console.log(response[x][0]);
                    //console.log(response[x][1]);
                    //console.log(response[x][2]);
                    buildOrders += "</div><!--close card-->";
                }
                if(typeof buildOrders !== 'undefined'){
                    //console.log(buildOrders);
                    document.getElementById("accordion").innerHTML = buildOrders;
                }                
            }
        }
    }
    xhr.open("GET", "./incl/getOrders.php", true);
    xhr.send();
}

let getCheckedBoxes = (chkboxName)=>{
                        //console.log(chkboxName);
                        //let prodNameChkBox = chkboxName.target.parentNode.previousElementSibling.previousElementSibling.previousElementSibling.firstChild.innerHTML;
                        let prodNameChkBox = chkboxName.getAttribute("data-product");
                        let prodPriceChkBox = chkboxName.getAttribute("data-price");
                        let prodShortDescChkBox = chkboxName.getAttribute("data-short_desc");
                        //let prodPriceChkBox = chkboxName.target.parentNode.previousElementSibling.lastChild.firstChild.innerHTML;
                        //console.log(prodNameChkBox);
                        //make money value into float by removing $ and ,
                        //prodPriceChkBox = prodPriceChkBox.replace('$','');
                        //prodPriceChkBox = prodPriceChkBox.replace(',','');
                        //console.log(prodPriceChkBox);            
                        let checkboxes = document.getElementsByName(prodNameChkBox+"_productCheckboxes");
                        let prodOpt = [];
                        // loop over them all
                        for (var i=0; i<checkboxes.length; i++) {
                          // And stick the checked ones onto an array...
                          if (checkboxes[i].checked) {
                             prodOpt.push(checkboxes[i].value);
                            }
                          }
                          // Return the array if it is non-empty, or null
                          console.log(prodOpt);
                          if((shoppingCart.itemsObj[prodNameChkBox] === undefined || shoppingCart.itemsObj[prodNameChkBox].quantity == 0 || shoppingCart.itemsObj[prodNameChkBox].options === undefined) && prodOpt.length != 0){
                              //TODO add input to specify quantity from product modal
                              shoppingCart.updateItemsObj(shoppingCart.itemsObj, prodNameChkBox, 1);
                              //add price to itemsObj.product
                              shoppingCart.itemsObj[prodNameChkBox].price = prodPriceChkBox;
                              shoppingCart.itemsObj[prodNameChkBox].short_desc = prodShortDescChkBox;
                              shoppingCart.calcTotalPrice(shoppingCart.itemsObj);
                              shoppingCart.priceArr = [];
                              Vue.set(shoppingCart.itemsObj[prodNameChkBox], 'options', prodOpt);
                              document.getElementById(prodNameChkBox).innerHTML = "Add to Cart";
                              document.getElementById(prodNameChkBox+"quantity").value = 1;
                            }else if(shoppingCart.itemsObj[prodNameChkBox] !== undefined){
                                //console.log(this.itemsObj[prodNameChkBox].options);
                                if(shoppingCart.itemsObj[prodNameChkBox].options.length > 0){
                                    let opts = shoppingCart.itemsObj[prodNameChkBox].options;
                                    let z;
                                    for (x in opts){
                                        z="";
                                        for (y in prodOpt){
                                            if(opts[x]!==prodOpt[y]){
                                                z = opts[x];
                                                break;
                                            }
                                            if(z!==null || z!==""){
                                                prodOpt.push(z);
                                            }        
                                        }

                                    }
                                    Vue.set(shoppingCart.itemsObj[prodNameChkBox], 'options', prodOpt);
                                    }
                              }
                          //Vue.set(this.itemsObj[prodNameChkBox], 'options', prodOpt);
                          shoppingCart.updateCartAjax();
                          shoppingCart.emptyOptions();
                          return prodOpt;
                        };

//enable Bootstrap tooltips
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

const cleanUpVue = (paramArrClean)=> {
    shoppingCart.cleanup(paramArrClean);
};



// ===============================================
// up button
// ===============================================
 function up(set) {
  const upBtn = document.createElement('div');
  let upBtnImg;

  upBtn.classList.add('up-btn', 'up-btn__hide');

  function showBtn(num) {
    if (document.documentElement.scrollTop >= num) {
      upBtn.classList.remove('up-btn__hide');
    } else {
      upBtn.classList.add('up-btn__hide');
    }
  }

  if (set) {

    if (set.src) {
      upBtnImg = document.createElement('img');
      upBtnImg.src = set.src;
      upBtnImg.classList.add('up-btn__img');
    } else {
      upBtnImg = document.createElement('div');
      upBtnImg.innerHTML = `
        <svg viewBox="0 0 448 512"><path fill="#fff" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path></svg>
      `;
      upBtnImg.classList.add('up-btn__img');
    }

    upBtn.style.top = set.top;
    upBtn.style.bottom = set.bottom;
    upBtn.style.left = set.left;
    upBtn.style.right = set.right;
    upBtn.style.background = set.bg;
    upBtn.style.width = set.width;
    upBtn.style.height = set.height;

    if (set.circle) {
      upBtn.classList.add('up-btn_circle');
    }

    document.body.append(upBtn);
    upBtn.append(upBtnImg);


    if (set.whenShow) {
      window.addEventListener('scroll', () => {
        showBtn(set.whenShow);
      });
    } else {
      window.addEventListener('scroll', () => {
        showBtn(400);
      });
    }

  } else {
    upBtnImg = document.createElement('div');
      upBtnImg.innerHTML = `
        <svg viewBox="0 0 448 512"><path fill="#fff" d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path></svg>
      `;
      upBtnImg.classList.add('up-btn__img');
    document.body.append(upBtn);
    upBtn.append(upBtnImg);

    window.addEventListener('scroll', () => {
      showBtn(400);
    });
  }

  upBtn.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
  });

}

up({

  // bottom position
  bottom: '20px',

  // right position
  right: '20px',

  // width
  width: '35px',

  // height
  height: '35px',

  // background color
  bg: '#6c757d',

  // custom icon
  src: './scripts/chevron-up-solid.svg',

  // distance from the top to show the back to top button
  whenShow: 400,

  // circular button?
  //circle: true
  
});



