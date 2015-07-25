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

		require './mail/PHPMailerAutoload.php';

		$text = array(
			'name'    => 'Автор заявки',
			'phone'   => 'Номер телефона',
			'email'   => 'Эл. почта',
			'company' => 'Компания',
			'message' => 'Сообщение',
			'vacancy' => 'Отклик на вакансию',
			'resume'  => 'Резюме'
		);

		$body = "<small>С сайта было отправлено сообщение следующего содержания:</small><br /><hr><br /><br />";

		foreach ($_REQUEST as $key => $value)
			if($text[$key]&&strlen($value)>0)
				$body .= $text[$key].': '.nl2br($value)."<br /><br />\r\n";

		foreach ($_FILES as $key => $value):
			if($text[$key]):
				$name  = $value['name'];
				$value = CFile::GetPath(CFile::SaveFile($value));
				$body .= $text[$key].': <a href="http://'.$_SERVER['HTTP_HOST'].$value.'">'.$name."</a><br /><br />\r\n";
			endif;
		endforeach;
		$body .= "<br /><hr><br />";

		if ($result['status'] == 'ok') {

			$emails = preg_split("/(,\s|,)/", COption::GetOptionString("grain.customsettings","group_".$_REQUEST["group_id"]));

			foreach ($emails as $email):
				$mail = new PHPMailer;
				$mail->isSendmail();
				$mail->CharSet = 'UTF-8';
				$mail->Subject = "Сообщение с сайта ".$_SERVER['HTTP_HOST'];
				$mail->setFrom("mailer@".$_SERVER['HTTP_HOST'], "Сайт ".$_SERVER['HTTP_HOST']);
				$mail->addAddress($email, $email);
				$mail->msgHTML($body);
				AddMessage2Log($email);
				//$mail->send();
			endforeach;

		}
}
print json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
