<?php

$result = array();
if( isset($_GET["INN"]) && !empty($_GET["INN"]) ){
	$INN = htmlspecialchars($_GET["INN"]);

	// создание нового ресурса cURL
	$ch = curl_init();
	// установка URL и других необходимых параметров
	curl_setopt($ch, CURLOPT_URL, "https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = json_encode(array("query" => $INN, "branch_type" => "MAIN"));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	//curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json',
		'Authorization: Token ff1e18b3ddc94c4589632221757c9b015d41c0c5'
	));
	// загрузка страницы и выдача её браузеру
	$resultCURL = curl_exec($ch);
	if(curl_errno($ch)){
		$result["success"] = false;
	    $result["data"] = "Ошибка curl: ".curl_error($ch);
	}else{
		$result["success"] = true;
		$arResultCURL = json_decode($resultCURL, true);
		if(!empty($arResultCURL["suggestions"]) && !empty($arResultCURL["suggestions"][0]["value"])){
			$result["data"] = $arResultCURL["suggestions"][0]["value"];
		}else{
			$result["data"] = "Организация по ИНН не найдена";
		}
	}
	// завершение сеанса и освобождение ресурсов
	curl_close($ch);
}else{
	$result["success"] = false;
	$result["data"] = "GET-параметр INN отсутствует";
}
echo json_encode($result);

?>