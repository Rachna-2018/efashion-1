
<?php

$method = $_SERVER['REQUEST_METHOD'];
//process only when method id post
if($method == 'POST')
{
	$requestBody = file_get_contents('php://input');
	$json = json_decode($requestBody);
	$com = $json->queryResult->parameters->command;
	$com = strtolower($com);
	
		
	if ($com == 'amountsold' or $com == 'margin' or $com == 'qtysold' or $com=='shoplist' or $com=='shopsale') 
	{
		$STATE= $json->queryResult->parameters->STATE;
		$STATE= strtoupper($STATE);
		$CITY= $json->queryResult->parameters->CITY;
		$CITY= strtoupper($CITY);
		/*$SHOPNAME= $json->queryResult->parameters->SHOPNAME;
		$SHOPNAME= strtoupper($SHOPNAME);
		$SHOPNAME = str_replace(' ', '', $SHOPNAME);*/
		$CITY = str_replace(' ', '', $CITY);
		if($CITY=="" )
		{
			$CITY='0';
			
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($STATE, $userespnose))
		{
			$STATE = 'ALL';
		}
		$userespnose = array("EACH", "EVERY","ALL");
		if(in_array($CITY, $userespnose))
		{
			$CITY = 'ALL';
		}
		
		/*else if (in_array($STATE, $userespnose,TRUE) and in_array($CITY, $userespnose,TRUE) ) 
		{
			$STATE = 'ALL'; 
		 	$CITY='ALL'; 
		}
		else if (in_array($STATE, $userespnose,FALSE) and $STATE!="" and in_array($CITY, $userespnose,TRUE))
		{
			$CITY = 'ALL';
			
		}
		else if (in_array($STATE, $userespnose,TRUE) and $CITY = ""))
		{
			$STATE = 'ALL';
			$CITY = '0';
		}*/
					
		$json_url = "http://74.201.240.43:8000/ChatBot/Sample_chatbot/EFASHION_DEV.xsjs?command=$com&STATE=$STATE&CITY=$CITY";		
		//echo $json_url;
		$username    = "SANYAM_K";
    		$password    = "Welcome@123";
		$ch      = curl_init( $json_url );
    		$options = array(
        	CURLOPT_SSL_VERIFYPEER => false,
        	CURLOPT_RETURNTRANSFER => true,
        	CURLOPT_USERPWD        => "{$username}:{$password}",
        	CURLOPT_HTTPHEADER     => array( "Accept: application/json" ),
    		);
    		curl_setopt_array( $ch, $options );
		$json = curl_exec( $ch );
		$someobj = json_decode($json,true);
		if($com == 'amountsold' or $com == 'margin' or $com == 'qtysold')
		{
			if ($com == 'amountsold')
				$distext = "Total sale value is of worth $";
			else if($com == 'margin')
				$distext = "Total profit value is of worth $";
			else if ($com == 'qtysold')
				$distext = "Total quantity sold of worth $";
			if($CITY !='0')
			{
				$discity = " for city ";
			}
			else
			{
				$discity = "";
			}
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $distext. $value["AMOUNT"].$discity.$value["CITY"]." in ".$value["STATE"];
				$speech .= "\r\n";
			 }
		}
		else if($com == 'shoplist')
		{
			foreach ($someobj["results"] as $value) 
			{
				$speech .= $value["SHOP_NAME"]." availabe in ".$value["CITY"]." in ".$value["STATE"];
				$speech .= "\r\n";
			 }
		}
			
	}
	
	
	$response = new \stdClass();
    	$response->fulfillmentText = $speech;
    	$response->source = "webhook";
	echo json_encode($response);

}
else
{
	echo "Method not allowed";
}

?>
