<?php
	require_once("phpmail.php");

	$from = "«М1» Юридическая компания";
	$email_from = "robot@m1.ru";

	function sendMail($deafult, $arFields, $title, $sendTelegram = false){

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

		$message = "<div><h3 style=\"color: #333;\">".$title."</h3>";

		foreach ($fields as $key => $value){
			$message .= "<div><p><b>".$key.": </b>".$value."</p></div>";
		}
			
		$message .= "</div>";

		if( $sendTelegram ){

			$messaggio = "";
			if($_SESSION['id']){
				$messaggio .= "Заявка №".$_SESSION['id']."\n";
			}

			if( $_SESSION["type"] == "card" ){
				$messaggio .= "<b>Оплачено картой</b>\n";				
			}else if($_SESSION["type"] == "account"){
				$messaggio .= "<b>Оплата на расчетный счет</b>\n";
			}else{
				$messaggio .= $subject."\n";
			}

			foreach ($fields as $key => $value){
				if( $key == "Сумма" ){
					$messaggio .= $key.": <b>".$value."</b>\n";
				}else{
					$messaggio .= $key.": ".$value."\n";
				}
			}
			sendTelegram($messaggio);
		}

		if( $sendTelegram ){
			$email_admin = "volkov@llc-pravo.ru, mike@kitaev.pro";
		}else{
			$email_admin = "info@m1.moscow, mike@kitaev.pro";
		}


		$result = send_mime_mail($GLOBALS["from"],$GLOBALS["email_from"],"",$email_admin,'UTF-8','UTF-8',$subject,$message,true);
		return $result;
	}

	function sendMailForClient($email_to, $subject, $title, $text){
		$message = "<div><h3 style=\"color: #333;\">".$title."</h3><p>".$text."</p></div>";
		$result = send_mime_mail("Сайт ".$GLOBALS["from"],$GLOBALS["email_from"],"",$email_to,'UTF-8','UTF-8',$subject,$message,true);
		return $result;
	}

	function sendTelegram($messaggio) {
		$chatID = "-1001150139270";
	    $token = "bot949586729:AAFB5Mpt6NCUoOI31nXPPvWZ9ZEOwxHvvS4";
	    $url = "https://api.telegram.org/" . $token . "/sendMessage?chat_id=" . $chatID;
	    $url = $url . "&parse_mode=HTML&text=" . urlencode($messaggio);
	    $ch = curl_init();
	    $optArray = array(
	            CURLOPT_URL => $url,
	            CURLOPT_RETURNTRANSFER => true
	    );
	    curl_setopt_array($ch, $optArray);
	    $result = curl_exec($ch);
	    curl_close($ch);
	}
?>