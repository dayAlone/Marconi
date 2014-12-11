<?
	require_once ($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php");
	use Bitrix\Highloadblock as HL;
	use Bitrix\Main\Entity;
	
	class Properties
	{
		private $iblock = array();

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("highloadblock");
	
			$dbHblock = HL\HighloadBlockTable::getList();
            while ($ib = $dbHblock->Fetch())
            	$this->iblocks[$ib['TABLE_NAME']] = (int)$ib['ID'];
		}

		private function GetHighloadElements($id)
		{
			$obCache   = new CPHPCache();
			$cacheLife = 86400; 
			$cacheID   = 'GetHighloadElements_'.$id; 
			$cachePath = '/'.$cacheID;

			if( $obCache->InitCache($cacheLife, $cacheID, $cachePath) ):

				$vars = $obCache->GetVars();
				$data = $vars['data'];

			else:

				$data    = array();
				$hlblock = HL\HighloadBlockTable::getById($id)->fetch();
				$entity  = HL\HighloadBlockTable::compileEntity($hlblock);
				$class   = $entity->getDataClass();

				$rsData = $class::getList(array(
					"select" => array("*"),
					"order"  => array("ID" => "ASC")
				));

				while($arData = $rsData->Fetch())
					$data[] = $arData;
				
				$obCache->EndDataCache(array('data' => $data));

			endif;
			
			return $data;
		}

		public function Action($file)
		{
			$data = Import::readFile($file, array('properties', 'property'));
			foreach ($data as $item):
				$code = $item->getAttribute('id');
				switch ($code) {
					case 'brands':
					case 'colors':
					case 'materials':
					case 'sizes':
						
						break;
				}
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
			
			$class  = __NAMESPACE__ .'\\'.$action['step'];
			$helper = new $class;

			$helper->Action($action['file'], $offset);

			$this->time = microtime(true) - $this->time;
			var_dump($this->time);
		}
		public function readFile($file, $element, $offset)
		{
			$path = file_get_contents($_SERVER['DOCUMENT_ROOT']."/import/upload/".$file);
			$dom  = new DOMDocument('1.0', 'utf-8');
			$dom->loadXML($path);
			$path = "";

			$xpath    = new DOMXPath($dom);
			$elements = $dom->getElementsByTagName($element[0])->item(0);
			$count    = $xpath->evaluate("count(".$element[1].")", $elements); 
		
			$start = 1;
			$end   = $start + 500;
			if($end > intval($count))
				$end = $count;

			$items = $xpath->evaluate($element[1]."[position() >= $start and not(position() > $end)]", $products);
			
			return $items;
		}
	}

	$x = new Import;
?>
