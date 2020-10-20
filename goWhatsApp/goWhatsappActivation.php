<?php
 /**
 * @file 		goWhatsappActivation.php
 * @brief 		API for Whatsapp Activation
 * @copyright 	Copyright (C) 2020 GOautodial Inc.
 * @author		Thom Bernarth Patacsil
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
*/

	$action = $goDB->escape($_REQUEST["action_whatsapp"]);
	
	//$query = "SELECT * FROM smtp_settings LIMIT 1;";
	$rsltv = $goDB->getOne('go_whatsapp_message');
	$exist = $goDB->getRowCount();
	$err_msg = $goDB->getLastError();

	//$exist = 1;
	if($exist <= 0){
		$query1 = "CREATE TABLE `go_whatsapp_message` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`messageid` varchar(50) NOT NULL,
			`body` text NOT NULL,
			`fromMe` tinyint(1) NOT NULL,
			`self` tinyint(1) NOT NULL,
			`isForwarded` tinyint(1) NOT NULL,
			`author` varchar(50) NOT NULL,
			`time` int(11) NOT NULL,
			`chatId` varchar(50) NOT NULL,
			`messageNumber` int(11) NOT NULL,
			`type` varchar(24) NOT NULL,
			`senderName` varchar(50) NOT NULL,
			`caption` varchar(255) NULL,
			`quotedMsgBody` text NOT NULL,
			`quotedMsgId` varchar(50) NOT NULL,
			`chatName` varchar(50) NOT NULL,
			`instanceId` int(11) NOT NULL,
			PRIMARY KEY (id)
			); ";
		
		$query2 = "CREATE TABLE `go_whatsapp_ack` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`messageid` varchar(50) NOT NULL,
			`queueNumber` int(11) NOT NULL,
			`chatId` varchar(50) NOT NULL,
			`status` varchar(24) NOT NULL,
			`instanceId` int(11) NOT NULL,
			PRIMARY KEY (id)
			);";

		$exec_query1 = $goDB->rawQuery($query1);
		$exec_query2 = $goDB->rawQuery($query2);
		
		if($exec_query1 && $exec_query2){
			// create enable_whatsapp in settings
			//$check_settings = mysqli_query($linkgo, "SELECT * FROM settings WHERE setting = 'enable_whatsapp';");
			$goDB->where('setting', 'enable_whatsapp');
			$check_settings = $goDB->get('settings');
			$setting_exist = $goDB->getRowCount();
			
			if($setting_exist <= 0){
				//$insert_enable_whatsapp = mysqli_query($linkgo, "INSERT INTO settings (setting, context, value) VALUES('enable_whatsapp', 'whatsapp_settings', '0');");
				$insertData = array(
					'setting' => 'enable_whatsapp',
					'context' => 'whatsapp_settings',
					'value' => '0'
				);
				$insert_enable_smtp = $goDB->insert('settings', $insertData);
			}
			
			$apiresults = array("result" => "success");
			$log_id = log_action($goDB, 'CREATE', $log_user, $ip_address, "Created Whatsapp Settings!", $log_group, $new_smtp_default_query);
		}else{
			$apiresults = array("result" => "error", "msg" => "An error has occured, please contact the System Administrator to fix the issue.", "query" => $insert_query);
		}
	}
	
	// second check if enable agent chat exists
	//$check_settings = mysqli_query($linkgo, "SELECT * FROM settings WHERE setting = 'enable_whatsapp';");
	$goDB->where('setting', 'enable_whatsapp');
	$check_settings = $goDB->get('settings');
	$setting_exist = $goDB->getRowCount();
	
	if($setting_exist <= 0){
		//$insert_enable_whatsapp = mysqli_query($linkgo, "INSERT INTO settings (setting, context, value) VALUES('enable_whatsapp', 'whatsapp_settings', '0');");
		$insertData = array(
			'setting' => 'enable_whatsapp',
			'context' => 'whatsapp_settings',
			'value' => '0'
		);
		$insert_enable_smtp = $goDB->insert('settings', $insertData);
	}
	
	$default_action = array(0, 1); // 0 = deactivate, 1 = activate
	if(in_array($action, $default_action)){
		//$action_whatsapp_query = "UPDATE settings SET value = '$action' WHERE setting = 'enable_whatsapp';";
		$updateData = array(
			'value' => $action
		);
		$goDB->where('setting', 'enable_whatsapp');
		$exec_action_whatsapp = $goDB->update('settings', $updateData);
		
		if($goDB->getRowCount() > 0){
			$apiresults = array("result" => "success", "query" => $querythom);
			if($action == 1)
				$act = "Enabled";
			else
				$act = "Disabled";
			$log_id = log_action($goDB, 'UPDATE', $log_user, $ip_address, "$log_user $act Whatsapp Settings!", $log_group, $goDB->getLastQuery());
		}else{
			$apiresults = array("result" => "error", "msq" => $new_whatsapp_default_query);
		}
	}else{
		$apiresults = array("result" => "error", "msg" => $goDB->getLastError());
	}
?>
