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

		$mail = new PHPMailer;
		$mail->isSendmail();
		$mail->CharSet = 'UTF-8';
		
		
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
				$name = $value['name'];
				$value = CFile::GetPath(CFile::SaveFile($value));
				$body .=$text[$key].': <a href="http://'.$_SERVER['HTTP_HOST'].$value.'">'.$name."</a><br /><br />\r\n";
			endif;
		endforeach;
		$body .= "<br /><hr><br />";

		$mail->Subject = "Сообщение с сайта ".$_SERVER['HTTP_HOST']; 
		$mail->setFrom("mailer@".$_SERVER['HTTP_HOST'], "Сайт ".$_SERVER['HTTP_HOST']);

		if ($result['status'] == 'ok') {
			$rs_user = CUser::GetList(
				($by = 'name'),
				($order = 'asc'),
				array(
					'GROUPS_ID' => array($_REQUEST["group_id"])
				)
			);

			while($ar_user = $rs_user->GetNext())
				$mail->addAddress($ar_user['EMAIL'], $ar_user['LOGIN']);
			
			$mail->msgHTML($body);
			$mail->send();
		}
}
print json_encode($result);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>