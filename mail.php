<?php
	require_once("phpmail.php");

	// global $from, $email_from, $email_admin;
	$from = "Юридическая компания “М1”";
	$email_from = "robot@m1.ru";
	$email_admin = "beatbox787@gmail.com";

	function sendMail($deafult, $arFields){

		$arDebtors = array(
			'physical' 		=> 'Физическим лицом',
			'legal' 		=> 'Юридическим лицом',
			'entrepreneur' 	=> 'Индивидуальным предпринимателем'
		);
		$fields = array();
		foreach ($deafult as $key => $value){
			if( isset($arFields[$key]) ){
				if($key == "debtor"){
					$fields[$value] = $arDebtors[$arFields[$key]];
				}else{
					$fields[$value] = $arFields[$key];
				}
			}
		}

		$i = 1;
		while( isset($arFields[''.$i]) ){
			$fields[$arFields[$i."-name"]] = $arFields[''.$i];
			$i++;
		}

		$subject = isset($arFields["subject"]) ? $arFields["subject"] : "Заявка на публикацию";
		$title = "Поступила заявка с сайта ".$GLOBALS["from"].":\n";

		$message = "<div><h3 style=\"color: #333;\">".$title."</h3>";

		foreach ($fields as $key => $value){
			$message .= "<div><p><b>".$key.": </b>".$value."</p></div>";
		}
			
		$message .= "</div>";

		$result = send_mime_mail("Сайт ".$GLOBALS["from"],$GLOBALS["email_from"],"",$GLOBALS["email_admin"],'UTF-8','UTF-8',$subject,$message,true);
		return $result;
	}

	function sendMailForClient($email_to, $subject, $title, $text){
		$message = "<div><h3 style=\"color: #333;\">".$title."</h3><p>".$text."</p></div>";
		$result = send_mime_mail("Сайт ".$GLOBALS["from"],$GLOBALS["email_from"],"",$email_to,'UTF-8','UTF-8',$subject,$message,true);
		return $result;
	}
?>