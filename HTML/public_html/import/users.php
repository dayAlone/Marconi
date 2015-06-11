<?
	define("NO_IP", true);
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS", true); 
	
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;
	
	set_time_limit(0);
	@ignore_user_abort(true);
	
	function mb_ucfirst($string, $e ='utf-8') { 
        if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) { 
            $string = mb_strtolower($string, $e); 
            $upper = mb_strtoupper($string, $e); 
            preg_match('#(.)#us', $upper, $matches); 
            $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e); 
        } else { 
            $string = ucfirst($string); 
        } 
        return $string; 
    }
	class Users
	{
		public function __construct()
		{

		}
		public function getData($raw)
		{
			// Группы пользователей
			$groups = array();
			if($raw[3] == 1 ):
				$groups[] = 9; // Франчайзи
			else:
				$groups[] = 5;
				switch ($raw[13]) {
					case 2:
						$groups[] = 10; // 5%
						break;
					case 3:
						$groups[] = 11; // 7%
						break;
					case 4:
						$groups[] = 12; // 10%
						break;
					case 5:
						$groups[] = 13; // 12%
						break;
					case 6:
						$groups[] = 14; // 16%
						break;
				}
			endif;
			$fields = array(
				'LOGIN'             => $raw[5],
				'EMAIL'             => $raw[5],
				'NAME'              => $raw[7],
				'LAST_NAME'         => $raw[27],
				'PASSWORD'          => $raw[2],
				'CONFIRM_PASSWORD'  => $raw[2],
				'GROUP_ID'          => $groups,
				'PERSONAL_GENDER'   => ($raw[64]=='men'?"M":"F"),
				'PERSONAL_BIRTHDAY' => (strlen($raw[51]) > 1 && $raw[51] != '0000-00-00'?date('d.m.Y', strtotime($raw[51])):""),
				'PERSONAL_PHONE'    => $raw[9].$raw[8],
				'PERSONAL_MOBILE'   => $raw[43].$raw[44], // what's up
				'WORK_PHONE'        => $raw[31].$raw[39],
				'WORK_COMPANY'      => $raw[10],
				'WORK_NOTES'        => $raw[12],
				'PERSONAL_NOTES'    => $raw[11],

			);
			return $fields;
		}
		public function Action($file)
		{
			$data = Import::getElements($file);
			foreach ($data as $raw) {
				$user   = new CUser;
				$fields = $this->getData($raw);
				$ID = $user->Add($fields);
				if (intval($ID) > 0)
					fwrite(STDERR, "\033[35m Пользователь успешно добавлен. \033[37m\r\n");
				else
					fwrite(STDERR, "\033[31m\033[4mCUser::Add ".strip_tags($user->LAST_ERROR)." — ".$fields['LOGIN']."\033[0m\n\r");
				die();
			}
			
			
		}
	}
	class Import
	{
		public static $time;

		private $lock;

		private $checkfile = false;

		const step = 10;

		private $steps = array('users');//, , , 'write-off');
		
		public function __construct($offset=0)
		{
			$offset = intval($offset);
			if($offset < 1)
				$offset = 1;

			$this->time = microtime(true);
			$this->lock = $_SERVER['DOCUMENT_ROOT'].'/import/.lock';

			if($this->checkfile):
				if(file_exists($this->lock))
					return;
				else
					file_put_contents($this->lock, '');
			endif;

			$offset = intval($offset);
			$files = array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/import/upload/'), array('..', '.', '.DS_Store'));
			


			foreach ($this->steps as $step):
				$matches = preg_grep('/^'.$step.'/', $files);
				if ( count($matches) > 0 ):
					$action = array('step'=>ucfirst($step), 'file' => current($matches));
					break;
				endif;
			endforeach;
			

			if( isset($action) ):
				$class  = $action['step'];
				$helper = new $class;

				fwrite(STDERR, "\r\nAction: \033[36m ".$action['step']."\r\n"); 
				$result = $helper->Action($action['file'], $offset);

				if(intval($result) == 0)
					unlink($_SERVER['DOCUMENT_ROOT'].'/import/upload/'.$action['file']);

				$this->time = microtime(true) - $this->time;
				$time = round($this->time,2);
				
				fwrite(STDERR,"\033[37mOffset: ".$result." | Time: \033[32m ".$time."\033[37m\r\n");
				unlink($this->lock);
				$result++;

				shell_exec("php ".$_SERVER['DOCUMENT_ROOT']."/import/users.php ".$result);
			else:
				unlink($this->lock);
				return;
			endif;
		}

		public function getElements($file, $element, $offset=false)
		{
			$csv = new CCSVData('R', true);
			$csv->LoadFile($_SERVER['DOCUMENT_ROOT']."/import/upload/".$file);

			if(isset($offset))
				$csv->SetPos($offset);

			$i = 0;
			while ($data = $csv->Fetch()) {
				$items[] = $data;
				$i++;
				if($i >= Import::step)
					break;
			}
			
			$end = $csv->GetPos();

			if($offset)
				return array('items'=>$items, 'offset'=>($end==$count?'end':$end));
			else
				return $items;
		}

		
	}

	$x = new Import($argv[1]);
?>
