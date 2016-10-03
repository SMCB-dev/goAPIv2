<?php

 ####################################################
 #### Name: goGetAllCampaignsAPI.php             ####
 #### Description: API to get campaign list      ####
 #### Version: 0.9                               ####
 #### Copyright: GOAutoDial Ltd. (c) 2011-2015   ####
 #### Written by: Jeremiah Sebastian V. Samatra  ####
 #### License: AGPLv2                            ####
 ####################################################


 	$url = "https://gadcs.goautodial.com/goAPI/goCampaigns/goAPI.php"; #URL to GoAutoDial API. (required)
        $postfields["goUser"] = "admin"; #Username goes here. (required)
        $postfields["goPass"] = "kam0teque1234"; #Password goes here. (required)
        $postfields["goAction"] = "getAllCampaigns"; #action performed by the [[API:Functions]]. (required)
        $postfields["responsetype"] = "json"; #json. (required)


	 $ch = curl_init();
	 curl_setopt($ch, CURLOPT_URL, $url);
	 curl_setopt($ch, CURLOPT_POST, 1);
	 curl_setopt($ch, CURLOPT_TIMEOUT, 100);
	 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	 curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
	 $data = curl_exec($ch); //var_dump($data);
	 curl_close($ch);
	 $output = json_decode($data);

	if ($output->result=="success") {
	   # Result was OK!
			for($i=0;$i<count($output->campaign_id);$i++){
				echo $output->campaign_id[$i]."</br>";
				echo $output->campaign_name[$i]."</br>";
				echo $output->dial_method[$i]."</br>";
				echo $output->active[$i]."</br>";
         			echo "</br>";
			}
	 } else {
	   # An error occured
	   	echo $output->result;
	}

?>