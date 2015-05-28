<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$err = array();

if (!$GLOBALS['APPLICATION']->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_code"]))
	$err['required'][] = 'captcha_word';

if ($err) {
	$result['status'] = 'error';
	$result['errors'] = $err;
} 
else
	$result['status'] = 'ok';

if($result['status'] == 'ok') {
		
		$EOL = "\r\n";
		$text = array(
			'name'    => 'Автор заявки',
			'phone'   => 'Номер телефона',
			'email'   => 'Эл. почта',
			'company' => 'Компания',
			'message' => 'Сообщение',
		);

		$body = "<br />
		С сайта было отправлено сообщение следующего содержания:<br />
		____________________________________________________________<br />
		<br />
		";

		foreach ($_REQUEST as $key => $value)
			if($text[$key]&&strlen($value)>0)
				$body .= $text[$key].': '.nl2br($value)."<br /><br />\r\n";
			

		$boundary = "--".md5(uniqid(time()));
		$un = strtoupper(uniqid(time()));
		$headers = "MIME-Version: 1.0;$EOL";   
		$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"$EOL";  
		$headers .= "From: ".$_SERVER['HTTP_HOST']." <mailer@".$_SERVER['HTTP_HOST'].">\r\n"; 
		$multipart  = "--$boundary$EOL";   
		$multipart .= "Content-Type: text/html; charset=UTF-8$EOL";   
		$multipart .= "Content-Transfer-Encoding: base64$EOL";   
		$multipart .= $EOL; // ðàçäåë ìåæäó çàãîëîâêàìè è òåëîì html-÷àñòè 
		$multipart .= chunk_split(base64_encode($body)); 
		$multipart .=  "$EOL--$boundary$EOL";   
		$multipart .= "Content-Type: application/octet-stream; name=\"$name\"$EOL";   
		$multipart .= "Content-Transfer-Encoding: base64$EOL";   
		//$multipart .= "Content-Disposition: attachment; filename = \"".$path."\"\n\n";   
		$multipart .= $EOL; 
		$multipart .= chunk_split(base64_encode($file));   
		$multipart .= "$EOL--$boundary--$EOL";

		$body .= "<br />
		____________________________________________________________<br />
		";

		$subject = "Заявка с сайта: ".$_REQUEST["theme"]; 
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';

		if ($result['status'] == 'ok') {
			$rs_user = CUser::GetList(
				($by = 'name'),
				($order = 'asc'),
				array(
				'GROUPS_ID' => array($_REQUEST["group_id"])
				)
			);

			while($ar_user = $rs_user->GetNext()) {
				mail($ar_user['EMAIL'], $subject, $multipart, $headers); 
			}

		}
}
print json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>