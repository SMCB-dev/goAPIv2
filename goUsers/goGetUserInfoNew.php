<?php
/**
 * @file        goGetUserInfoNew.php
 * @brief       API to get specific user details 
 * @copyright   Copyright (c) 2018 GOautodial Inc.
 * @author      Demian Lizandro A. Biscocho
 * @author      Alexander Jim H. Abenoja
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/
    @include_once ("goAPI.php");
    
	$log_user 								= $session_user;
	$log_group 								= go_get_groupid($session_user, $astDB);    

    // POST or GET Variables
    $user_id 								= $astDB->escape($_REQUEST['user_id']);
    //$log_ip = $astDB->escape($_REQUEST['log_ip']);

        
    // Check user_id if its null or empty
	if (empty($log_user) || is_null($log_user)) {
		$apiresults 						= array(
			"result" 							=> "Error: Session User Not Defined."
		);
	} elseif (empty($user_id) || is_null($user_id)) {
		$err_msg 							= error_handle("40001");
        $apiresults 						= array(
			"code" 								=> "40001",
			"result" 							=> $err_msg
		);
    } else {
        if (checkIfTenant($log_group, $goDB)) { 
			$astDB->where("user_group", $log_group); 
		}
        
        $cols 								= array(
			"user_id",
			"user",
			"full_name",
			"email",
			"user_group",
			"active",
			"user_level",
			"phone_login",
			"phone_pass",
			"voicemail_id",
			"hotkeys_active",
			"vdc_agent_api_access",	
			"agent_choose_ingroups",
			"vicidial_recording_override",
			"vicidial_transfers",
			"closer_default_blended",
			"agentcall_manual",
			"scheduled_callbacks",
			"agentonly_callbacks",
			"agent_lead_search_override"        
        );
        
        $astDB->where("user_id", $user_id);
        $fresults 							= $astDB->getOne("vicidial_users", $cols);
        //$user 							= $fresults["user"];
        
        $colsgo 							= array(
			"userid",
			"avatar",
			"gcal",
			"calendar_apikey",
			"calendar_id"        
        );
        
        $goDB->where("userid", $user_id);
        $fresultsgo 						= $goDB->getOne("users", $colsgo);
		
		if ($goDB->count > 0) {
			$data 							= array_merge($fresults, $fresultsgo); 
		} else { 
			$data 							= $fresults; 
		}               
        //$log_id = log_action($goDB, 'VIEW', $log_user, $log_ip, "Viewed info of User $user", $log_group);
        
		if (!empty($data)) { 
			$apiresults 					= array(
				"result" 						=> "success", 
				"data" 							=> $data
			); 
		} else { 
			$apiresults 					= array(
				"result" 						=> "Error: User Group doesn't exist."
			); 
		} 
	}

?>
