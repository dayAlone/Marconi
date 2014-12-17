<?
	$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	define("NO_KEEP_STATISTIC", true);
	define("NOT_CHECK_PERMISSIONS",true); 
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;
	set_time_limit(0);
	@ignore_user_abort(true);
	
	class Properties
	{
		private $iblock;

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("highloadblock");
	
			$this->iblocks = Import::getIBlocks();

			$dbHblock = HL\HighloadBlockTable::getList();
            while ($ib = $dbHblock->Fetch())
            	$this->iblocks[$ib['TABLE_NAME']] = (int)$ib['ID'];
		}

		public function Action($file)
		{
			$data = Import::getElements($file, array('properties', 'property'));
			
			foreach ($data as $item):
				$code = $item->getAttribute('id');
				switch ($code) {
					case 'brands':
					case 'categories':
					case 'colors':
					case 'materials':
					case 'types':
					case 'sizes':
						$data  = Import::getHighloadElements($this->iblocks[$code]);
						$items = $item->getElementsByTagName('item');
						foreach ($items as $el):
							$id = $el->getAttribute('id');
							if(!$data[$id]):

								$name  = $el->getElementsByTagName('name')->item(0)->nodeValue;
								$value = $el->getElementsByTagName('value')->item(0)->nodeValue;
								$array = array('id'=>$id, 'name'=>$name, 'value'=>$value);
								
								$result = Import::addHighloadElement($this->iblocks[$code], $array);

							endif;
						endforeach;
					break;
					case 'sections':
						$data  = Import::getIBlockSections($this->iblocks['products']);
						$items = $item->getElementsByTagName('item');
						foreach ($items as $el):
							$id = $el->getAttribute('id');
							if(!$data[$id]):
								$name  = $el->getElementsByTagName('name')->item(0)->nodeValue;
								$array = array('XML_ID'=>$id, 'NAME'=>$name);
								
								$result = Import::addIBlockSection($this->iblocks['products'], $array);
							endif;
						endforeach;
					break;
				}
			endforeach;

			return 0;
		}
	}

	class Products
	{
		private $iblock, $sections, $categories, $types, $brands;

		private $counter = array('add'=>0, 'update'=>0, 'offers'=>0, 'error'=>0);

		private $remove = array('XS', 'S', 'M', 'L', 'XL');

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("highloadblock");
			
			$remove = array();
			foreach ($this->remove as $i)
				$remove[] = "/(\s(".$i.")|_".strtolower($i).")$/";
			$this->remove = $remove;

			$this->iblocks    = Import::getIBlocks();
			$dbHblock = HL\HighloadBlockTable::getList();
            while ($ib = $dbHblock->Fetch())
            	$this->iblocks[$ib['TABLE_NAME']] = (int)$ib['ID'];

			$this->sections   = Import::getIBlockSections($this->iblocks['catalog'],3);
			$this->categories = Import::getHighloadElements($this->iblocks['categories'], true);
			$this->types      = Import::getHighloadElements($this->iblocks['types'], true);
			$this->brands     = Import::getHighloadElements($this->iblocks['brands'], true);
		}
		private function addParentCatagory(&$parent, $array)
		{
			if(!is_array($parent))
				$parent = array('ID'=>$parent, 'CHILD'=>array());
			if($parent['ID']):
				$array['IBLOCK_SECTION_ID'] = $parent['ID'];
				$parent['CHILD'][$array['XML_ID']] = Import::addIBlockSection($this->iblocks['products'], $array);
			endif;
		}
		private function getOffer(&$fields)
		{
			$offer = array(
					'NAME'            => $fields['PROPERTY_VALUES']['NOTE']." ".$fields['PROPERTY_VALUES']['NAME'],
					'CODE'            => Cutil::translit($fields['PROPERTY_VALUES']['NOTE']." ".$fields['PROPERTY_VALUES']['NAME'], "ru"),
					'XML_ID'          => $fields['XML_ID'],
					'PREVIEW_PICTURE' => $fields['PROPERTY_VALUES']['PICTURES']['n0'],
					'PROPERTY_VALUES' => array(
							'CODE'      => $fields['PROPERTY_VALUES']['CODE'],
							'NOTE'      => $fields['PROPERTY_VALUES']['NOTE_FULL'],
							'COLOR'     => $fields['PROPERTY_VALUES']['COLOR'],
							'SIZE'      => $fields['PROPERTY_VALUES']['OFFER_SIZE'],
							'ARTNUMBER' => $fields['PROPERTY_VALUES']['ARTNUMBER'],
							'PICTURES'  => $fields['PROPERTY_VALUES']['PICTURES']
					)
			);
			$fields['NAME']   = preg_replace($this->remove, '', $fields['NAME']);
			$fields['CODE']   = preg_replace($this->remove, '', $fields['CODE']);
			$fields['XML_ID'] = $fields['PROPERTY_VALUES']['ARTNUMBER'];
			foreach (array('NAME', 'CODE', 'COLOR', 'PICTURES', 'OFFER_SIZE', 'NOTE_FULL') as $item)
				unset($fields['PROPERTY_VALUES'][$item]);
			return $offer;
		}
		private function getExist($fields)
		{
			$array = array(
				'ID'     => $fields['ID'],
				'NAME'   => $fields['NAME'],
				'XML_ID' => $fields['XML_ID']
			);
			foreach ($fields['PROPERTY_VALUES'] as $key => $prop):
				switch ($key):
					case 'NOTE';
					case 'CODE':
					case 'MATERIAL':
					case 'ARTNUMBER':
						$array[$key] = $prop;
						break;
				endswitch;
			endforeach;
			return $array;
		}
		private function getData($item)
		{
			$xml_id    = $item->getAttribute('id');
			$artnumber = $item->getElementsByTagName('artnumber')->item(0)->nodeValue;
			$tmp       = $item->getElementsByTagName('namePrint')->item(0)->nodeValue;
			$note      = substr($tmp, 0, strpos($tmp, $artnumber)-1);

			$fields = array(
				'XML_ID'      => $xml_id,
				'DETAIL_TEXT' => $item->getElementsByTagName('description')->item(0)->nodeValue
			);

			$props = array(
				'NAME'       => $item->getElementsByTagName('name')->item(0)->nodeValue,
				'CODE'       => intval($item->getAttribute('code')),
				'ARTNUMBER'  => $artnumber,
				'NOTE'       => $note,
				'NOTE_FULL'  => preg_replace($this->remove, '', $tmp),
				'COLOR'      => array(),
				'PICTURES'   => array(),
			);
			
			$raw = $item->getElementsByTagName('property');

			$sections = array();

			foreach ($raw as $prop):
				$id    = $prop->getAttribute('id');
				$value = $prop->getAttribute('value');
				
				switch ($id):
					case 'color':
						$props['COLOR'][] = $value;
					break;
					case 'SizeForWeb':
						if(strlen($value) > 0)
							$props['SIZE'] = $value;
					break;
					case 'size':
						$props['OFFER_SIZE'] = $value;
					break;
					case 'material':
					case 'brand':
						$props[strtoupper($id)] = $value;
						break;
					case 'section':
						$parent = $this->sections[$value];
						if(isset($parent))
							$sections['first'] = $value;
						break;
					case 'category':
							$parent = &$this->sections[$sections['first']];
							if(!isset($parent['CHILD'][$value]))
								$this->addParentCatagory($parent, $this->categories[$value]);
							$sections['id'] = $this->categories[$value]['ID'];
							$sections['second'] = $value;
						break;
					case 'type':
						$parent = &$this->sections[$sections['first']]['CHILD'][$sections['second']];
						if(!isset($parent['CHILD'][$value]))
							$this->addParentCatagory($parent, $this->types[$value]);
						$props['SECTION_'.$sections['id']] = $value;
						$fields['IBLOCK_SECTION_ID'] = $parent['CHILD'][$value];
						break;
				endswitch;
			endforeach;

			$name = $note;
			if($this->brands[$props['BRAND']]['NAME'])
				$name .= ' '.$this->brands[$props['BRAND']]['NAME'];
			$name .= " ".$artnumber;

			$fields["NAME"] = $name;
			$fields["CODE"] = Cutil::translit($name, "ru");

			$slug   = str_replace(' ','_', preg_replace($this->remove, '', $item->getElementsByTagName('name')->item(0)->nodeValue));
			$images = array_merge(glob($_SERVER['DOCUMENT_ROOT']."/import/photos/".$slug.".jpg"), glob($_SERVER['DOCUMENT_ROOT']."/import/photos/".$slug."_[0-9].jpg"));
			
			foreach ($images as $key=>$image):
				if($key==0)
					$fields['PREVIEW_PICTURE'] = CFile::MakeFileArray($image);
				$props['PICTURES']['n'.$key] = array("VALUE"=>CFile::MakeFileArray($image));
			endforeach;

			$fields["PROPERTY_VALUES"] = $props;

			return $fields;
		}
		public function Action($file, $offset)
		{
			$offers   = array();
			$products = array();
			$data     = Import::getElements($file, array('products', 'product'), $offset);
			
			foreach ($data['items'] as $item):
				$offers[]   = $item->getAttribute('id');
				$products[] = $item->getElementsByTagName('artnumber')->item(0)->nodeValue;
			endforeach;

			$offers   = Import::getIBlockElements($this->iblocks['offers'], array('XML_ID' => $offers), array('PROPERTY_SIZE', "PROPERTY_ARTNUMBER", "PROPERTY_COLOR"));
			$products = Import::getIBlockElements($this->iblocks['products'], array('XML_ID' => $products), Array("PROPERTY_SIZE", "PROPERTY_MATERIAL", "PROPERTY_BRAND", "PROPERTY_SECTION_1", "PROPERTY_SECTION_2", "PROPERTY_SECTION_3", "IBLOCK_SECTION_ID", "PROPERTY_CODE", "PROPERTY_ARTNUMBER", "PROPERTY_NOTE_SHORT", "PROPERTY_NOTE_FULL" ));

			foreach ($data['items'] as $item):

				$fields = $this->getData($item);
				$offer  = $this->getOffer($fields);

				$props  = &$fields["PROPERTY_VALUES"];

				if(isset($products[$fields['XML_ID']])||isset($offers[$offer['XML_ID']])):
					
					$exist = $products[$fields['XML_ID']];

					$update = false;
					$diff   = array_diff($props, $exist);
					
					if($fields['IBLOCK_SECTION_ID'] != $exist['IBLOCK_SECTION_ID']):
						$raw = new CIBlockElement;
						$raw->Update($exist['ID'], array('IBLOCK_SECTION_ID'=>$fields['IBLOCK_SECTION_ID']));
						$update = true;
					endif;

					if(count($diff)>0):
						CIBlockElement::SetPropertyValuesEx($exist['ID'], $this->iblocks['products'], $diff);
						$update = true;
					endif;
					
					if(!isset($offers[$offer['XML_ID']])):
						$offer['PROPERTY_VALUES']['CML2_LINK'] = $exist['ID'];
						$id = Import::addIBlockElement($this->iblocks['offers'], $offer);
						if(intval($id)>0):
							$this->counter['offers']++;
						else:
							$this->counter['error']++;
						endif;	
					else:
						$exist_offer = $offers[$offer['XML_ID']];
						if($offers[$fields['XML_ID']]['SIZE'] != $props['OFFER_SIZE']):
							$update = true;
							CIBlockElement::SetPropertyValuesEx($offers[$fields['XML_ID']]['ID'], $this->iblocks['offers'], array('SIZE'=>$props['OFFER_SIZE']));
						endif;
					endif;

					if($update):
						$this->counter['update']++;
					endif;

				else:
					
					$id = Import::addIBlockElement($this->iblocks['products'], $fields);
					
					if(intval($id)>0): 
						$this->counter['add']++;
						$fields['ID'] = $id;
						$products[$fields['XML_ID']] = $this->getExist($fields);
						if($offer):
							$offer['PROPERTY_VALUES']['CML2_LINK'] = $id;
							$id = Import::addIBlockElement($this->iblocks['offers'], $offer);
							if(intval($id)>0):
								$this->counter['offers']++;
							else:
								$this->counter['error']++;
							endif;			
						endif;
					else:
						$this->counter['error']++;
					endif;

				endif;

			endforeach;

			fwrite(STDERR, "\033[37mOffers: ".$this->counter['offers']." \033[35m Update: ".$this->counter['update']." \033[32m Add: ".$this->counter['add']." \033[31m Error: ".$this->counter['error']." \033[37m\r\n");

			if($data['offset'] != 'end')
				return $data['offset'];
			else
				return 0;

		}
	}

	class Import
	{
		public static $time;

		private $lock;

		private $checkfile = false;

		const step = 100;

		private $steps = array('properties', 'products');//, 'prices', 'counts', 'write-off');
		
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

				shell_exec("/Applications/MAMP/bin/php/php5.6.1/bin/php ".$_SERVER['DOCUMENT_ROOT']."/import/index.php ".$result);
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
			
			$data    = array();
			$parents = array();
			$array   = Array('IBLOCK_ID'=>$id, 'ACTIVE'=>'Y', '=<DEPTH_LEVEL'=>$depth, 'CHECK_PERMISSIONS' => 'N');
			$raw     = CIBlockSection::GetList(Array('DEPTH_LEVEL'=>'ASC'), $array, true);

			while($section = $raw->GetNext()):
				switch ($section['DEPTH_LEVEL']):
					case '1':
							$data[$section['XML_ID']] = $section['ID'];
							$parents[$section['ID']]  = $section['XML_ID'];
						break;
					default:
							$parent = $section['IBLOCK_SECTION_ID'];
							$parents[$section['ID']] = array('PARENT'=>$parent, 'ID'=>$section['XML_ID']);
							if( is_array($parents[$parent]) ):
								$code = $parents[$parent]['ID'];
								$parent = &$data[$parents[$parents[$parent]['PARENT']]]['CHILD'][$parents[$parent]['ID']];
								if(!is_array($parent))
									$parent = array('ID'=>$parent, 'CHILD'=>array($section['XML_ID']=>$section['ID']));
								else
									$parent['CHILD'][$section['XML_ID']] = $section['ID'];
							else:
								$code = $parents[$parent];
								if(!is_array($data[$code]))
									$data[$parents[$parent]] = array('ID'=>$data[$parents[$parent]], 'CHILD'=>array($section['XML_ID']=>$section['ID']));
								else
									$data[$parents[$parent]]['CHILD'][$section['XML_ID']] = $section['ID'];
							endif;
						break;
				endswitch;
			endwhile;
			
			return $data;
		}

		public function getIBlockElements($id, $filter, $fields = false)
		{
			$data = array();
			$arSelect = array_merge(Array("ID", "NAME", "XML_ID"), $fields);
			
			$arFilter = array_merge(Array("IBLOCK_ID"=>$id, "ACTIVE"=>"Y", 'CHECK_PERMISSIONS' => 'N'), $filter);
			$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
			
			while($el = $res->Fetch()):
				$array = array();
				foreach ($el as $key => $item)
					if(
							(!preg_match("/^PROPERTY_(.*)_ID/", $key) || strstr($key,'CML2_LINK_XML'))
							&& !strstr($key, '_DESCRIPTION')
							&& $item
						)
						$array[str_replace(array('PROPERTY_', '_VALUE'), array('',''), $key)] = $item;
				
				$data[$el['XML_ID']] = $array;
			endwhile;

			return $data;
		}

		public function addIBlockSection($id, $data)
		{
			$raw   = new CIBlockSection;
			$array = Array(
				"ACTIVE"    => "Y",
				"IBLOCK_ID" => $id
			);
			$array = array_merge($array, $data);
			return $raw->Add($array);
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

		public function getHighloadElements($id, $remove=false)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 86400; 
			$cacheID   = 'getHighloadElements_'.$id; 
			$cachePath = '/'.$cacheID;

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
