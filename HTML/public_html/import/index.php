<?
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;
	
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

			return true;
		}
	}

	class Products
	{
		private $iblock, $sections, $categories, $types;

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("highloadblock");
	
			$this->iblocks    = Import::getIBlocks();
			$dbHblock = HL\HighloadBlockTable::getList();
            while ($ib = $dbHblock->Fetch())
            	$this->iblocks[$ib['TABLE_NAME']] = (int)$ib['ID'];

			$this->sections   = Import::getIBlockSections($this->iblocks['catalog'],3);
			$this->categories = Import::getHighloadElements($this->iblocks['categories'], true);
			$this->types      = Import::getHighloadElements($this->iblocks['types'], true);
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
		public function Action($file)
		{
			$data = Import::getElements($file, array('products', 'product'), true);

			foreach ($data['items'] as $item):

				$artnumber = $item->getElementsByTagName('artnumber')->item(0)->nodeValue;
				$tmp       = $item->getElementsByTagName('namePrint')->item(0)->nodeValue;
				$note      = substr($tmp, 0, strpos($tmp, $artnumber)-1);
				$name      = $note.' '.str_replace($artnumber.' ','', $item->getElementsByTagName('name')->item(0)->nodeValue);
				$slug      = str_replace(' ','_', $item->getElementsByTagName('name')->item(0)->nodeValue);

				$fields = array(
					'IBLOCK_ID' => $this->iblocks['products'],
					'NAME'      => $name,
				);

				$props = array(
					'CODE'       => intval($item->getAttribute('code')),
					'ARTNUMBER'  => $artnumber,
					'NOTE_SHORT' => $note,
					'NOTE_FULL'  => $tmp,
					'COLOR'      => array()
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
							$props['SIZE'] = $value;
						break;
						case 'material':
						case 'brand':
							$props[strtoupper($id)] = $value;
							break;
						case 'section':
							if(isset($this->sections[$value]))
								$sections['first'] = $value;
							break;
						case 'category':
								$parent = &$this->sections[$sections['first']];
								if(!isset($parent['CHILD'][$value]))
									$this->addParentCatagory($parent, $this->categories[$value]);
								$sections['second'] = $value;
							break;
						case 'type':
							$parent = &$this->sections[$sections['first']]['CHILD'][$sections['second']];
							if(!isset($parent['CHILD'][$value]))
								$this->addParentCatagory($parent, $this->types[$value]);
							$fields['IBLOCK_SECTION_ID'] = $parent['CHILD'][$value];
							break;
					endswitch;
				endforeach;

				var_dump($slug);
				die();

			endforeach;
		}
	}

	class Import
	{
		public static $time;

		private $steps = array('properties', 'products', 'prices', 'counts', 'write-off');
		
		public function __construct($offset)
		{
			$this->time = microtime(true);

			$offset = intval($offset);
			$files = array_diff(scandir($_SERVER['DOCUMENT_ROOT'].'/import/upload/'), array('..', '.', '.DS_Store'));

			foreach ($this->steps as $step):
				$matches = preg_grep('/^'.$step.'/', $files);
				if ( count($matches) > 0 ):
					$action = array('step'=>ucfirst($step), 'file' => current($matches));
					break;
				endif;
			endforeach;
			
			$class  = $action['step'];
			$helper = new $class;

			$result = $helper->Action($action['file'], $offset);

			if($result)
				unlink($_SERVER['DOCUMENT_ROOT'].'/import/upload/'.$action['file']);

			$this->time = microtime(true) - $this->time;
			var_dump($this->time);
		}

		public function getElements($file, $element, $offset)
		{
			$path = file_get_contents($_SERVER['DOCUMENT_ROOT']."/import/upload/".$file);
			$dom  = new DOMDocument('1.0', 'utf-8');
			$dom->loadXML($path);
			$path = "";

			$xpath    = new DOMXPath($dom);
			$elements = $dom->getElementsByTagName($element[0])->item(0);
			$count    = $xpath->evaluate("count(".$element[1].")", $elements); 
		
			$start = 1;
			$end   = $start + 50;
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
					'TYPE'    =>'catalog',
					'ACTIVE'  =>'Y'
			    ), true
			);
			
			while($item = $raw->Fetch())
				$data[$item['CODE']] = $item['ID'];

			return $data;
		}

		public function getIBlockSections($id, $depth = 1)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 86400; 
			$cacheID   = 'getIBlockSections_'.$id; 
			$cachePath = '/'.$cacheID;

			if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

				$vars = $obCache->GetVars();
				$data = $vars['data'];
			
			elseif( $obCache->StartDataCache() ):

				$data    = array();
				$parents = array();
				$array   = Array('IBLOCK_ID'=>$id, 'ACTIVE'=>'Y', '=<DEPTH_LEVEL'=>$depth);
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
				
				$obCache->EndDataCache(array('data' => $data));
				

			endif;
			
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
			var_dump($array);
			return $raw->Add($array);
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
					unset($tmp[$key]['ID']);
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

	$x = new Import;
?>
