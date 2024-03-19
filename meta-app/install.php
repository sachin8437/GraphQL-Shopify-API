<?php
// https://developer.brandclever.in/j-j/webhook/meta-app/install.php?shop=returnagain
// Set variables for our request
$shop = $_GET['shop'];
$api_key = "8d625153718aa6415656f35be10968ee";
$scopes = "read_orders,write_products,read_products,read_product_listings,read_script_tags,write_script_tags,read_themes,write_themes,read_content,write_content";
$redirect_uri = "https://developer.brandclever.in/j-j/webhook/meta-app/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
//die();