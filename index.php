<?php
 
$data = file_get_contents("php://input");
echo $data;

if (!empty($data)) {
    $dataArray = json_decode($data, true); // true for associative array
    //print_r($dataArray);
    
	$dataArray['eventId'];
	$dataArray['data']['formId'];
	$dataArray['data']['formName'];

	// Original time string
	$dateOrg = $dataArray['data']['createdAt'] ;
 
	//$timeString = "2024-03-18T12:29:53.000Z";

	// Create a DateTime object with the original time string
	$dateTime = new DateTime($dateOrg);

	// Format the DateTime object into 12-hour format
	$dateModify = $dateTime->format('Y-m-d h:i:s A');

	// Output the time in 12-hour format
	//echo $date;
	 


	$dataArray['data']['fields'];
		foreach ($dataArray['data']['fields'] as $key => $fieldData) {
			
			file_put_contents("post2.php", print_r($fieldData, true), FILE_APPEND);

			$fielsLable = $fieldData['label'];
			$fieldVal = $fieldData['value'];
			$fieldType = $fieldData['type'];
			$fieldkey = $fieldData['key'];

			 if($fieldType == 'INPUT_TEXT' || $fieldType == 'INPUT_PHONE_NUMBER' || $fieldType == 'INPUT_EMAIL' || $fieldType == 'INPUT_NUMBER' || $fieldType == 'INPUT_LINK' || $fieldType == 'TEXTAREA'){
				
				echo $fielsLable." <==> ".$fieldType." <==> ".$fieldVal;
				
				if($fielsLable == 'Name'){
					$sallerName = $fieldVal;
					echo " Saller Name ".$sallerName;
				}

				if($fielsLable == 'Company'){
					$sallerName2 = $fieldVal;
					echo " Saller Name 2 ".$sallerName2;
				}

				if($fielsLable == 'Phone'){
					$sallerPhone = $fieldVal;
					echo " Saller Phone ".$sallerPhone;
				}

				if($fielsLable == 'Email'){
					$sallerEmail = $fieldVal;
					echo " Saller Email ".$sallerEmail;	
				}

				if($fielsLable == 'Address'){
					if($fieldkey == 'question_2jjk1p'){
						$sallerAddress = $fieldVal;	
						echo " Saller Address ".$sallerAddress;	
					} 
				}

				if($fielsLable == 'Unit, Apt, Suite'){
					if($fieldkey == 'question_xVVQ4G'){
						$sallerAddress2 = $fieldVal;
						echo " Saller Address 2 ".$sallerAddress2;
					}		
				}

				if($fielsLable == 'City'){
					if($fieldkey == 'question_Z99Vv0'){
						$sallerCity = $fieldVal;	
						echo " Saller City ".$sallerCity;
					}	
				}

				if($fielsLable == 'State'){
					if($fieldkey == 'question_NqqVMG'){
						$sallerState = $fieldVal;	
						echo " Saller State ".$sallerState;
					}
				}

				if($fielsLable == 'Zip Code'){
					if($fieldkey == 'question_q55Bo2'){
						$sallerZipCode = $fieldVal;	
						echo " Saller Zip ".$sallerZipCode;
					}
				}

				if($fielsLable == 'Country'){
					$sallerCountry = $fieldVal;
					echo " Saller Country ".$sallerCountry;	
				}

				if($fielsLable == 'Handing over the item'){
					$localPickup = $fieldVal;	
					echo " Saller Pickup ".$localPickup;	
				}

				if($fielsLable == 'Please share a link of the website the item was purchased or from manufacturer'){
					$link = $fieldVal;
					echo " Saller Link ".$link;	
				}

				if($fielsLable == 'Item Weight'){
					$shpWeight = $fieldVal;	
					echo " Saller weight ".$shpWeight;
				}
				
				if($fielsLable == 'Whats the price in you would like to sell it for'){
					$price = $fieldVal;	
					echo " Saller Price ".$price;
				}

				if($fielsLable == "What is the 'Brand' name of your item?"){
					$title = $fieldVal;	
					echo " Title ".$title;
				}

				if($fielsLable == "Please provide a detailed description of your item."){
					$description = $fieldVal;	
					echo " Title ".$description;
				}

				
			 }
			 
			 if($fieldType == 'DROPDOWN'){

				if($fielsLable == 'Which category best describes your item?'){
					 $fieldData['value'][0];  
						echo " Category best is >> ".$dropVal;
					 
					foreach($fieldData['options'] as $optValue){
						if($fieldData['value'][0] == $optValue['id']){
							echo $optValue['id']."<br>";
							 $tag = $optValue['text']." ";
						}
						
						
					}
					 
					echo " Saller Tag ".$tag;
				}

				if($fielsLable == 'What is the ideal occasion for your item?'){
					 
					foreach($fieldData['options'] as $optValue){
						if($fieldData['value'][0] == $optValue['id']){
							echo $optValue['id']."<br>";
							$tag2 = $optValue['text']." ";
						}
					}
					 
					echo " Saller Tag 2 ".$tag2;
				}

				
			 }
 
			// FILE_UPLOAD
			if($fieldType == 'FILE_UPLOAD'){
				if($fielsLable == 'Please upload a photo/ photos of your item.'){
					if( $fieldkey == 'question_e55ZNe'){
						$imageUrl1 = $fieldData['value'][0]['url'];
						$imageName1 = $fieldData['value'][0]['name'];
					}
					if( $fieldkey == 'question_Arllyo'){
						$imageUrl2 = $fieldData['value'][0]['url'];
						$imageName2 = $fieldData['value'][0]['name'];
					}
					if( $fieldkey == 'question_BEGGe4'){
						$imageUrl3 = $fieldData['value'][0]['url'];
						$imageName3 = $fieldData['value'][0]['name'];
					} 
				}
			  
			}
 		}

 		//echo ' Title => '.$title.' Tag  =>  '.$tag.' Name  =>  '.$sallerName.' Email  => '.$sallerEmail.' Phone  => '.$sallerPhone.' Address  => '.$sallerAddress.' Add 2  =>  '.$sallerAddress2.' Zip  =>  '.$sallerZipCode.' City  =>  '.$sallerCity.' Price  =>  '.$price.' Weight  =>  '.$shpWeight.' Url  =>  '.$imageUrl;

		 /************************
		 * 
		 *  Create New Product 
		 * 
		 * ************************/

		// $title = "Product Custom";
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://returnagain.myshopify.com/admin/api/2024-01/graphql.json',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{"query":"mutation MyMutation {\\r\\n  productCreate(\\r\\n    input: {title: \\"'.$title.'\\", status: ACTIVE, tags: \\"'.$tag.'\\", productType: \\"'.$tag.'\\", vendor: \\"'.$title.'\\", metafields:[ { namespace: \\"custom\\", key: \\"link\\", value: \\"'.$link.'\\" }, {namespace: \\"custom\\", key: \\"seller_name\\", value: \\"'.$sallerName.'\\"}, {namespace: \\"custom\\", key: \\"seller_email\\", value: \\"'.$sallerEmail.'\\"},{ namespace: \\"custom\\", key: \\"seller_phone_number\\", value: \\"'.$sallerPhone.'\\" }, { namespace: \\"custom\\", key: \\"local_pickup\\", value: \\"true\\" }, { namespace: \\"custom\\", key: \\"address\\", value: \\"'.$sallerAddress.'\\" }, { namespace: \\"custom\\", key: \\"address_line_2\\", value: \\"'.$sallerAddress2.'\\" }, { namespace: \\"custom\\", key: \\"zip_code\\", value: \\"'.$sallerZipCode.'\\" }, { namespace: \\"custom\\", key: \\"city\\", value: \\"'.$sallerCity.'\\" }, { namespace: \\"custom\\", key: \\"state\\", value: \\"'.$sallerState.'\\" }, { namespace: \\"custom\\", key: \\"date_listed\\", value: \\"'.$dateModify.'\\" }  ], descriptionHtml: \\"'.$description.'\\", variants: {price: \\"'.$price.'\\", weight: '.$shpWeight.' }, }\\r\\n     media: [{originalSource: \\"'.$imageUrl1.'\\", mediaContentType: IMAGE, alt: \\"'.$imageName1.'\\"}, {originalSource: \\"'.$imageUrl2.'\\", mediaContentType: IMAGE, alt: \\"'.$imageName2.'\\"}, {originalSource: \\"'.$imageUrl3.'\\", mediaContentType: IMAGE, alt: \\"'.$imageName3.'\\"}]\\r\\n  ) {\\r\\n    product {\\r\\n\\t  id\\r\\n      title\\t\\r\\n      bodyHtml\\r\\n    }\\r\\n  }\\r\\n}","variables":{}}',
		  CURLOPT_HTTPHEADER => array(
			'X-Shopify-Access-Token: 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',
			'Content-Type: application/json'
		  ),
		));
 
		$response = curl_exec($curl);

		
		echo $response;
		$resArray = json_decode($response, true);
		file_put_contents("post2.php", print_r($resArray, true), FILE_APPEND);
		curl_close($curl);
		
}
 
 ?>