<?php

 ####################################################
 #### Name: goAddListAPI.php                     ####
 #### Description: API to add new List		 ####
 #### Version: 0.9                               ####
 #### Copyright: GOAutoDial Ltd. (c) 2011-2015   ####
 #### Written by: Jeremiah Sebastian V. Smatra	 ####
 #### License: AGPLv2                            ####
 ####################################################
	
	 $url = "https://encrypted.goautodial.com/goAPI/goCarriers/goAPI.php"; # URL to GoAutoDial API file
         $postfields["goUser"] = "goautodial"; #Username goes here. (required)
         $postfields["goPass"] = "JUs7g0P455W0rD11214"; #Password goes here. (required)
         $postfields["goAction"] = ""; #action performed by the [[API:Functions]]
         $postfields["responsetype"] = "json"; #json. (required)
	 $postfields["hostname"] = $_SERVER['REMOTE_ADDR']; #Default value
	 $postfields["carrier_id"] = ""; #Desired carrier ID (required)
	 $postfields["carrier_name"] = ""; #Desired name (required)
	 $postfields["carrier_description"] = ""; #Desired description (required)
	 $postfields["protocol"] = ""; #'SIP','Zap','IAX2',or 'EXTERNAL' (required)
	 $postfields["server_ip"] = ""; #Desired server ip (required)
	 $postfields["active"] = ""; #Y or N (required)

         $postfields["item"] = "$values";

	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	 curl_setopt($ch, CURLOPT_POST, 1);
	 curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	 $data = curl_exec($ch);
	 curl_close($ch);
	 $output = json_decode($data);
	
//	print_r($data);

	if ($output->result=="success") {
	   # Result was OK!
		echo "Added New Carrier ID: ".$carrier_id;	
	 } else {
	   # An error occured
	   	echo $output->result;
	}

?>
