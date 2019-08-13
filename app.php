<?php
	require_once("phpmail.php");

	if( count($_POST) ){
		$applicant = htmlspecialchars($_POST["applicant"]);
		$debtor = htmlspecialchars($_POST["debtor"]);
		if($debtor == "physical"){
			$name = htmlspecialchars($_POST["name"]);
		}else{
			$INN = htmlspecialchars($_POST["INN"]);
		}
		$phone = htmlspecialchars($_POST["phone"]);
		$email = htmlspecialchars($_POST["email"]);

		//Сохранить данные в сессии
		session_start();
		$_SESSION['applicant'] = $applicant;
		$_SESSION['debtor'] = $debtor;
		$_SESSION['name'] = $name;
		$_SESSION['INN'] = $INN;
		$_SESSION['phone'] = $phone;
		$_SESSION['email'] = $email;

		$email_admin = "rom4es.test@gmail.com";
		// $email_admin = "soc.taxi.35@gmail.com";

		$from = "Юридическая компания “М1”";
		$email_from = "robot@m1.ru";

		$arDebtors = array(
			"physical"=>"Физическим лицом",
			"legal"=>"Юридическим лицом",
			"entrepreneur"=>"Индивидуальным предпринимателем"
		);

		$deafult = array(
			"applicant"=>"Заявитель является",
			"debtor"=>"Должник является",
			"name"=>"Имя",
			"INN"=>"ИНН",
			"phone"=>"Телефон",
			"email"=>"E-mail"
		);

		$fields = array();

		foreach ($deafult as $key => $value){
			if( isset($_POST[$key]) ){
				if($key == "debtor"){
					$fields[$value] = $arDebtors[$_POST[$key]];
				}else{
					$fields[$value] = $_POST[$key];
				}
			}
		}

		$i = 1;
		while( isset($_POST[''.$i]) ){
			$fields[$_POST[$i."-name"]] = $_POST[''.$i];
			$i++;
		}

		$subject = $_POST["subject"];

		$title = "Поступила заявка с сайта ".$from.":\n";

		$message = "<div><h3 style=\"color: #333;\">".$title."</h3>";

		foreach ($fields  as $key => $value){
			$message .= "<div><p><b>".$key.": </b>".$value."</p></div>";
		}
			
		$message .= "</div>";
		
		if(send_mime_mail("Сайт ".$from,$email_from,$name,$email_admin,'UTF-8','UTF-8',$subject,$message,true)){	
			echo "1";
		}else{
			echo "0";
		}

	}else{
		echo "0";
	}
?>