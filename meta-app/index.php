<?php
include 'inc/functions.php';
include 'db/db.php';

$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
krsort($requests);

$token = "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";
$shop = "returnagain";

$ch = curl_init();

$api_url = "https://$shop.myshopify.com/admin/api/2023-07/shop.json";

curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$headers = array();
$headers[] = 'X-Shopify-Access-Token: ' . $token;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
} else {
   // echo 'Response: ' . $result;  Now it's working
}

curl_close($ch);

$array_data = json_decode($result, true);
$shop_id = $array_data['shop']['id'];
$shop_name = $array_data['shop']['name'];
$shop_email = $array_data['shop']['email'];
$shop_domain = $array_data['shop']['domain'];
$shop_customer_email = $array_data['shop']['customer_email'];
  echo "<span style='color: green;'> Shop Name :: ".$shop_name."</span>";

$collectionlist = shopify_call($token, $shop, "/admin/api/2023-07/custom_collections.json", array(), 'GET');
$collectionlist = json_decode($collectionlist['response'], JSON_PRETTY_PRINT);
$collection_id = $collectionlist['custom_collections'][0]['id'];
$collection_title = $collectionlist['custom_collections'][0]['title'];
$collection_handle = $collectionlist['custom_collections'][0]['handle'];
//echo "Collection id => ". $collection_id;
//echo "<br>";

$collects = shopify_call($token, $shop, "/admin/api/2023-07/collects.json", array("collection_id" => $collection_id), 'GET');
$collects = json_decode($collects['response'], JSON_PRETTY_PRINT);

foreach ($collects as $collect) {
    foreach ($collect as $key => $value) {

        $products = shopify_call($token, $shop, "/admin/api/2023-07/products/" . $value['product_id'] . ".json", array(), 'GET');

        $product = json_decode($products['response'], JSON_PRETTY_PRINT);
        //echo "<pre>"; print_r($product);
        $images = shopify_call($token, $shop, "/admin/api/2023-07/products/" . $value['product_id'] . "/images.json", array(), 'GET');
        $images = json_decode($images['response'], JSON_PRETTY_PRINT);
        $image = $images['images'][0]['src'];
        $title = $product['product']['title'];
    }
}

$theme = shopify_call($token, $shop, "/admin/api/2023-07/themes.json", array(), 'GET');
$theme = json_decode($theme['response'], JSON_PRETTY_PRINT);

foreach ($theme as $cur_theme) {
    foreach ($cur_theme as $keys => $values) {
        if ($values['role'] === 'main') {
            $array = array(
                'asset' => array(
                    'key' => 'templates/index.liquid',
                    'value' => ''
                )
            );
            $assets = shopify_call($token, $shop, "/admin/api/2023-07/themes/" . $values['id'] . "/assets.json", $array, 'PUT');
            $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);
        }
    }
}
 
$script_array = array(
    'script_tag' => array(
        'event' => 'onload',
        'src' => 'https://developer.brandclever.in/j-j/webhook/meta-app/scripts/script.js'
    )
);
$scriptTag = shopify_call($token, $shop, "/admin/api/2023-07/script_tags.json", $script_array, 'POST');
$scriptTag = json_decode($scriptTag['response'], JSON_PRETTY_PRINT);

// $lib_array = array(
//     'script_tag' => array(
//         'event' => 'onload',
//         'src' => 'https://code.jquery.com/jquery-1.7.1.min.js'
//     )
// );
// $scriptLib = shopify_call($token, $shop, "/admin/api/2023-07/script_tags.json", $lib_array, 'POST');
// $scriptLib = json_decode($scriptLib['response'], JSON_PRETTY_PRINT);


$query = "SELECT * FROM shopify_diy_assist WHERE id = 1";
$connection = $conn->query($query);
if(isset($_POST['submit'])) { // $_SERVER["REQUEST_METHOD"] == "POST"
    $diy_assist = $_POST['diy-assist-checkbox'];
    $diy_publishkey = $_POST['diy-assist-publishkey']; 
    $diy_securitytoken = $_POST['diy-assist-securitytoken'];
    $diy_discount_type = $_POST['discount_type'];
    $diy_discount_price = $_POST['discount_price'];
    // $query = "SELECT * FROM shopify_diy_assist WHERE id = 1";
    // $connection = $conn->query($query);
    if($connection->num_rows>0){
        $update_data = "UPDATE shopify_diy_assist SET diy_assist_checkbox='$diy_assist', diy_assist_publishkey='$diy_publishkey', diy_assist_securitytoken='$diy_securitytoken', discount_type='$diy_discount_type', discount_price='$diy_discount_price' WHERE id = 1";
        if ($conn->query($update_data)){
             // header("Location: javascript:history.go(-1)");
             header("Location: https://developer.brandclever.in/j-j/webhook/meta-app/create_section.php"); 
        }else{
            echo "not updated";
        }
    }else{
        $data = "INSERT INTO shopify_diy_assist (diy_assist_checkbox,diy_assist_publishkey,diy_assist_securitytoken,discount_type,  discount_price) VALUES ('$diy_assist','$diy_publishkey','$diy_securitytoken','$diy_discount_type','$diy_discount_price')";
        if ($conn->query($data)) {
            // header("Location: javascript:history.go(-1)");
            header("Location: https://developer.brandclever.in/j-j/webhook/meta-app/create_section.php");
            // $page = $_SERVER['PHP_SELF'];
            //   $sec = "20";
            //   header("Refresh: $sec; url=$page");
        } else {
            echo "Error: " . $data . "<br>" . $conn->error;
        }
    }
}
if($connection->num_rows>0){
    while($row = $connection->fetch_assoc()){
        $diy_assist_value = $row['diy_assist_checkbox'];
        $diy_publishkey_value = $row['diy_assist_publishkey'];
        $diy_securitytoken_value = $row['diy_assist_securitytoken'];
        $diy_discount_type_value = $row['discount_type'];
        $diy_discount_price_value = $row['discount_price'];
        $row_id = $row['id'];
        // echo "<pre>";
        // print_r($row);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>DIY Assist Form</title>
<link rel="stylesheet" href="style.css">
<style type="text/css">
.inpt_assit input{padding: 10px 0px 11px 9px; border-radius: 5px; border: 1px solid #dddada; width: 100%; }
.select_products_discount h3 {margin: 10px 0px 10px; text-align: center; font-size: 24px; }
#discount-type {padding: 10px 0px 11px 9px; border-radius: 5px; border: 1px solid #dddada; width: 100%; }

.create_form label {font-size: 15px; width: 100%; max-width: 150px; }
.create_form label {font-size: 15px; width: 100%; max-width: 150px; }

.inpt_assit input[type="checkbox"] {width: 50px; }
.create_form {width: 80%; margin: auto; }

#select_product {padding: 10px 0px 10px 9px; border: 1px solid #dddada; border-radius: 5px; font-size: 11px; width: 100%; }
.submit_btn input {background: #313131; color: #fff; width: 100% !important; border-radius: 5px; padding: 13px; border: 1px solid; }
.create_form  label {font-size: 15px; }
.inpt_assit {width: 100%; padding: 12px 0; display: flex; align-items: flex-start; gap: 10px; }
#selectedProduct {min-height: 110px; }
.select_assist {width: 100%; padding: 12px 0; display: flex; align-items: flex-start; gap: 10px; }
.aplly_all_divv {text-align: center; font-size: 18px; margin: 5px 0px; }
 
.hidden {display: none; } 
</style>
</head>
<body>
	<div class="create_form">
	  <form method="POST">
        <div class="inpt_assit">
            <label for="diy-assist-checkbox">DIY Assist Checkbox:</label>
            <input type="checkbox" name="diy-assist-checkbox" value="1" <?php if ($diy_assist_value == 1) echo "checked"; ?>><span>( If enable the DIY Assist Discount rule, Then it will be working. )</span>
        </div>
        <div class="inpt_assit">
            <label for="diy-assist-publishkey">DIY Assist Publish Key:</label>
            <input type="text" name="diy-assist-publishkey" value="<?php echo $diy_publishkey_value; ?>">
        </div>
        <div class="inpt_assit">
            <label for="diy-assist-securitytoken">DIY Assist Security Token:</label>
            <input type="text" name="diy-assist-securitytoken" value="<?php echo $diy_securitytoken_value; ?>">
        </div>
        <div class="select_assist">
            <label for="diy-assist-publishkey">Discount Type:</label>
            <select name="discount_type" id="discount-type" onchange="validateInput()">
              
                  <?php if($diy_discount_type_value != ''){?>
                  <option value="<?php echo $diy_discount_type_value; ?>"><?php echo $diy_discount_type_value; ?></option>
                <?php }else{?>
                    <option value="">Select any one</option>
                    <?php
                }?>
                  <option value="Decimal">Decimal</option>
                  <option value="Percentage">Percentage</option>
            </select> 
        </div>
        <div class="inpt_assit">
            <label for="diy-assist-publishkey">Add Discount Price:</label>
            <input type="text" id="discount-price" name="discount_price" value="<?php echo $diy_discount_price_value; ?>"> 
        </div>
        <div class="submit_btn">
            <input type="submit" name="submit" value="Submit">
        </div>
        <div class="inpt_assit">
            <span id="processingID" style="color:#0e8af0;"></span>
        </div>
    </form>
	</div>
</body>
<script>

 function validateInput() {
    var selectedOption = document.getElementById("discount-type").value;
    var inputField = document.getElementById("discount-price");
    if (selectedOption === "Decimal") {
        inputField.setAttribute("pattern", "^\\d+(\\.\\d{1,2})?$");
        inputField.setAttribute("title", "Enter a valid decimal number with up to two decimal places");
    } else if (selectedOption === "Percentage") {
        // Allow valid percentage values
        inputField.setAttribute("pattern", "^[0-9]+%?$");
        inputField.setAttribute("title", "Enter a valid percentage (e.g., 10% or 10)");
    }
    inputField.value = "";
}

var submitBtn = document.querySelector('.submit_btn');
var myDiv = document.getElementById('processingID');
// Create a text node
var textNode = document.createTextNode('Your request is under process, please wait!...');

// Add a click event listener to the element
submitBtn.addEventListener('click', function () { 
    myDiv.appendChild(textNode);
});

 
</script>
</html>