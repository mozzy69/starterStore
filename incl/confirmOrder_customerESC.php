<?php

$bodyStart="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"en-GB\">
<head>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	<meta charset=\"utf-8\">
	<title>Order Confirmation</title>
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <style type=\"text/css\">
        body, p, div {
          font-family: inherit;
          font-size: 14px;
          color: #999999; 
          font-family: Arial, Helvetica, sans-serif; 
          font-size:16px;
          padding-left: 24px;    
        }
        
        .em-font-head-main{
          font-size:32px; 
          text-align: left; 
          padding-left: 24px;
          padding-top: 32px;
          font-weight : 100;
        }
        
        .em-font-head-sec{
          font-size:18px; 
          text-align: left; 
          padding-left: 26px;  
          font-weight : 100;    
        }
        
        table{
            width: 100%;
            background-color: #ffffff;
        }
        
        .em-margin-top{
            margin-top : 16px;
        }
        
        .em-cl-faded{
            color : #f7f7f7;
        }
        
        .em-bgcl-primary{
            background-color: #0275d8;
        }
        
        .em-bgcl-muted{
            background-color: #999999;
        }
        
        .em-cl-muted{
            color : #999999;
        }
        
        .em-text-center{
            text-align: center;
            width : 33%;
        }
        
        .em-trackOrderButton{
            background-color:#999999; 
            border-radius: 6px; 
            color:#ffffff; 
            display:inline-block; 
            font-size:18px; 
            font-weight:normal; 
            letter-spacing:0px; 
            line-height:normal; 
            padding:12px 18px 12px 18px; 
            text-align:center; 
            text-decoration:none; 
            font-family:inherit;
            float: left;
            margin-left: 24px;
            border: none;
        }
        
        .em-lineItems tr:nth-child(even){
            background-color: #dddddd;
        }
        
        .em-grandTotal{
            text-align: right;
            font-size: 18px;
            padding-top: 24px;
            margin-bottom: 6px;
        }
        
        .em-grandTotal-sec{
            text-align: right;
            font-size: 24px;
            margin-top: 0px;
            color: #0275d8;
        }
    </style>    
</head>

<body style=\"background-color: #f7f7f7;\">
    <main>
    <table>
      <tr>
        <th>
            <table class=\"em-bgcl-primary\">
              <tr>
                  <th class=\"em-cl-faded em-font-head-main\"><h2 style=\"margin-bottom: 6px;\">Thank you for your order!</h2></th>
              </tr>
              <tr>
                <td class=\"em-cl-faded em-font-head-sec\"><h3 style=\"margin-top: 16px;\">Relax, while we take care of the rest for you.</h3></td>        
              </tr>
              <tr>
                  <td style=\"padding-bottom: 24px;\"><a class=\"em-trackOrderButton\" href=\"#\">Track your order</a></td>
              </tr>
            </table>
        </th>
      </tr>
      <tr>
        <td>
            <table>
              <tr>
                  <th class=\"em-cl-muted em-font-head-sec\"><h4>Order Summary</h4>
                  <hr></th>
              </tr>
              <tr>
                <td class=\"em-cl-muted\">
                    <p>Transaction Number : #";
 
$bodyStart01 = "</p>
                    <!--<p>Expected Delivery Date : 2021/01/05 </p>-->
                    <p>Delivery Details : ";
 
$bodyStart02 = "    </p>
                </td>        
              </tr>
            </table>
            <table class=\"em-lineItems\">
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>  
              </tr>";

$bodyEnd = "</table>
            <table>
              <tr>
                  <th><h4 class=\"em-grandTotal\">Grand Total</h4></th>
              </tr>
              <tr>
                <td><h4 class=\"em-grandTotal-sec\">";

$bodyEnd01= "</h4></td>
              </tr>
            </table>
        </td>        
      </tr>
      <tr>
        <td class=\"em-bgcl-primary\">
        <table class=\"em-bgcl-muted em-margin-top\">
          <tr>
            <td class=\"em-text-center em-cl-faded\">Shop</td>
            <td class=\"em-text-center em-cl-faded\">Support</td>
            <td class=\"em-text-center em-cl-faded\">Tracking</td>
          </tr>
        </table> 
        <table class=\"em-bgcl-primary em-margin-top\" style=\"margin-bottom: 16px;\">
          <tr>
            <td class=\"em-text-center em-cl-faded\">The Awesome Shop</td>
          </tr>
          <tr>
            <td class=\"em-text-center em-cl-faded\">Buster Drive Lane, Klienerville, 4598</td>
          </tr>
          <tr>
            <td class=\"em-text-center em-cl-faded\">Disclaimer</td>
          </tr>    
        </table>      
        </td>
      </tr>
    </table>
    </main>
</body>
</html>";

    
function confirmOrder_customer($param, $orderNum, $param2, $delivery, $param3, $lineItems, $param4, $grandT, $param05){
    return $param . $orderNum . $param2 . $delivery . $param3 . $lineItems . $param4 . $grandT . $param05;
}

?>                        