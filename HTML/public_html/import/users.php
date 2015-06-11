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

	class Import
	{
		public static $time;

		private $lock;

		private $checkfile = false;

		const step = 1000;

		private $steps = array('properties', 'products', 'counts', 'prices');//, , , 'write-off');
		
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

				shell_exec("php ".$_SERVER['DOCUMENT_ROOT']."/import/index.php ".$result);
			else:
				unlink($this->lock);
				return;
			endif;
		}

		public function getElements($file, $element, $offset=false)
		{
			$path = iconv('windows-1251', 'UTF-8', file_get_contents($_SERVER['DOCUMENT_ROOT']."/import/upload/".$file));
			
			$dom  = new DOMDocument('1.0', 'utf-8');
			$dom->loadXML($path);
			$path = "";

			$xpath    = new DOMXPath($dom);
			$elements = $dom->getElementsByTagName($element[0])->item(0);
			$count    = $xpath->evaluate("count(".$element[1].")", $elements); 

			$start = 1;
			if(intval($offset)>0)
				$start = $offset;
			$end   = $start + Import::step;
			if($end > intval($count))
				$end = $count;
			
			$items = $xpath->evaluate($element[1]."[position() >= $start and not(position() > $end)]", $products);

			if($offset)
				return array('items'=>$items, 'offset'=>($end==$count?'end':$end));
			else
				return $items;
		}

		public function getIBlocks()
		{
			$data = array();
			$raw  = CIBlock::GetList(
			    Array(), 
			    Array(
					'TYPE'              =>'catalog',
					'CHECK_PERMISSIONS' => 'N'
			    ), true
			);
			
			while($item = $raw->Fetch())
				$data[$item['CODE']] = $item['ID'];
			
			return $data;
		}

		public function getIBlockSections($id, $depth = 1)
		{
			$sections = array();
			$data     = array();
			$array    = Array('IBLOCK_ID'=>$id, 'ACTIVE'=>'Y', '=<DEPTH_LEVEL'=>$depth, 'CHECK_PERMISSIONS' => 'N');
			$sort     = Array("LEFT_MARGIN"=>"ASC");
			if($depth == 1)
				$sort = Array("ID"=>"ASC");
			$raw      = CIBlockSection::GetList($sort, $array, true, array('ID', 'NAME', 'CODE', 'XML_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID'));

			while($section = $raw->GetNext()):
				$sections[$section['ID']] = $section;
			endwhile;
			foreach ($sections as $s):
				if(strlen($s['XML_ID']) < 36)
					$s['XML_ID'] = $s['CODE'];
					
				switch ($s['DEPTH_LEVEL']):
					case '1':
						$data[$s['XML_ID']] = $s['ID'];
					break;
					case '2':
						$parent = $sections[$s['IBLOCK_SECTION_ID']]['XML_ID'];
						if( !is_array($data[$parent]) )
							$data[$parent] = array('ID' => $data[$parent], 'CHILD' => array());
						$data[$parent]['CHILD'][$s['XML_ID']] = $s['ID'];
					break;
					case '3':
						$second = $sections[$s['IBLOCK_SECTION_ID']];
						$first = $sections[$second['IBLOCK_SECTION_ID']]['XML_ID'];
						
						if( !is_array($data[$first]['CHILD'][$second['XML_ID']]) )
							$data[$first]['CHILD'][$second['XML_ID']] = array('ID' => $data[$first]['CHILD'][$second['XML_ID']], 'CHILD' => array());
						$data[$first]['CHILD'][$second['XML_ID']]['CHILD'][$s['XML_ID']] = $s['ID'];

					break;
				endswitch;
			endforeach;
			
			return $data;
		}

		public function getIBlockElements($id, $filter, $fields = false)
		{
			$data = array();
			$arSelect = array_merge(Array("ID", "NAME", "XML_ID", "IBLOCK_SECTION_ID"), $fields);
			
			$arFilter = array_merge(Array("IBLOCK_ID"=>$id, 'CHECK_PERMISSIONS' => 'N'), $filter);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			
			while($el = $res->Fetch()):
				$array = array('IBLOCK_SECTION'=>array());
				foreach ($el as $key => $item)
					if(
							(!preg_match("/^PROPERTY_(.*)_ID/", $key) || strstr($key,'CML2_LINK_XML'))
							&& !strstr($key, '_DESCRIPTION')
							&& $item
						)
						$array[str_replace(array('PROPERTY_', '_VALUE'), array('',''), $key)] = $item;
				
				$raw = CIBlockElement::GetElementGroups($el['ID']);

				while($s = $raw->Fetch())
					$array['IBLOCK_SECTION'][] = $s['ID'];

				$data[$el['XML_ID']] = $array;
			endwhile;

			return $data;
		}

		public function addIBlockSection($id, $data)
		{
			$raw   = new CIBlockSection;
			$array = Array(
				"ACTIVE"    => "Y",
				"IBLOCK_ID" => $id,
				"CODE"      => Translit::UrlTranslit($data["NAME"])
			);
			$array = array_merge($array, $data);
			if($id = $raw->Add($array)):
				return $id;
			else:
				var_dump($raw->LAST_ERROR);
			endif;

		}

		public function addIBlockElement($id, $data)
		{
			$raw   = new CIBlockElement;
			$array = Array(
				"ACTIVE"    => "Y",
				"IBLOCK_ID" => $id
			);
			$array = array_merge($array, $data);
			$id = $raw->Add($array);
			if(intval($id)>0)
				return $id;
			else
				fwrite(STDERR, "\033[31m\033[4maddIBlockElement ".strip_tags($raw->LAST_ERROR)." — ".$data['CODE']."\033[0m\n\r");
				return;
		}

		public function getAllArtnumbers($id)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 60*60; 
			$cacheID   = 'getAllArtnumbers'; 
			$cachePath = '/'.$cacheID;
			if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):
				$vars = $obCache->GetVars();
				$data = $vars['data'];
			elseif( $obCache->StartDataCache() ):
				$data = array();
				$arSelect = Array("ID", "PROPERTY_ARTNUMBER");
				$arFilter = Array("IBLOCK_ID"=>$id, 'CHECK_PERMISSIONS' => 'N', '<=DATE_CREATE' => ConvertTimeStamp(time()-60*60*24*60, "FULL"));
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
				while($el = $res->Fetch()):
					$art = $el['PROPERTY_ARTNUMBER_VALUE'];
					if(!in_array($art, $data))
						$data[] = $art;
					endwhile;
				$obCache->EndDataCache(array('data' => $data));
			endif;
			return $data;
		}

		public function getHighloadElements($id, $remove=false, $clear=false)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 86400; 
			$cacheID   = 'getHighloadElements_'.$id; 
			$cachePath = '/'.$cacheID;

			if($clear)
				BXClearCache(true, $cachePath);

			if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

				$vars = $obCache->GetVars();
				$data = $vars['data'];
			
			elseif( $obCache->StartDataCache() ):

				$data    = array();
				$hlblock = HL\HighloadBlockTable::getById($id)->fetch();
				$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
				$class   = $entity->getDataClass();

				$rsData = $class::getList(array(
					"select" => array("*"),
					"order"  => array("ID" => "ASC")
				));

				while($arData = $rsData->Fetch())
					$data[$arData['UF_XML_ID']] = $arData;
				
				$obCache->EndDataCache(array('data' => $data));
				
			endif;
			
			if($remove):
				$tmp = array();
				foreach ($data as $key=>$item):
					$tmp[$key]=array();
					foreach ($item as $k => $el)
						$tmp[$key][str_replace('UF_','',$k)] = $el;
				endforeach;
				return $tmp;
			endif;

			return $data;
		}
		
		public function addHighloadElement($id, $data)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 86400; 
			$cacheID   = 'getHighloadElements_'.$id; 
			$cachePath = '/'.$cacheID;

			if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):
				BXClearCache(true, $cachePath);
			endif;


			$hlblock = HL\HighloadBlockTable::getById($id)->fetch();
			$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
			$class   = $entity->getDataClass();
			$array   =  array('UF_NAME' => $data['name'], 'UF_XML_ID'=> $data['id']);

			if(strlen($data['value']))
				$array['UF_VALUE'] = $data['value'];

			$result = $class::add($array);
			
			if ($result->isSuccess())
				return $id;
		}
	}

	$x = new Import($argv[1]);
?>
