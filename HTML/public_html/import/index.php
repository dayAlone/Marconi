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

	class Translit
	{
	    function Transliterate($string)
	    {
	        $cyr = array(
	                "Щ",  "Ш", "Ч", "Ц","Ю", "Я", "Ж", "А","Б","В","Г","Д","Е","Ё","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х", "Ь","Ы","Ъ","Э","Є","Ї",
	                "щ",  "ш", "ч", "ц","ю", "я", "ж", "а","б","в","г","д","е","ё","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х", "ь","ы","ъ","э","є","ї"
	        );
	        $lat = array(
	                "Shh","Sh","Ch","C","Ju","Ja","Zh","A","B","V","G","D","Je","Jo","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","Kh","'","Y","`","E","Je","Ji",
	                "shh","sh","ch","c","ju","ja","zh","a","b","v","g","d","je","jo","z","i","j","k","l","m","n","o","p","r","s","t","u","f","kh","'","y","`","e","je","ji"
	        );
	        for ($i=0; $i < count($cyr); $i++)
	        {
	            $c_cyr = $cyr[$i];
	            $c_lat = $lat[$i];
	            $string = str_replace($c_cyr, $c_lat, $string);
	        }
	        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]e/", "\${1}e", $string);
	        $string = preg_replace("/([qwrtpsdfghklzxcvbnmQWRTPSDFGHKLZXCVBNM]+)[jJ]/", "\${1}'", $string);
	        $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
	        $string = preg_replace("/^kh/", "h", $string);
	        $string = preg_replace("/^Kh/", "H", $string);
	        return $string;
	    }
	    function UrlTranslit($string)
	    {
	        $string = preg_replace("/[_\s\.,?!\[\](){}]+/", "_", $string);
	        $string = preg_replace("/-{2,}/", "__", $string);
	        $string = preg_replace("/_-+_/", "__", $string);
	        $string = preg_replace("/[_\-]+$/", "", $string);
	        $string = Translit::Transliterate($string);
	        $string = ToLower($string);
	        $string = preg_replace("/j{2,}/", "j", $string);
	        $string = preg_replace("/[^0-9a-z_\-]+/", "", $string);
	        return $string;
	    }
	}

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
					case 'sale'       :
					case 'types'      :
					case 'sizes'      :
					case 'brands'     :
					case 'colors'     :
					case 'materials'  :
					case 'tradeline'  :
					case 'categories' :
						$data  = Import::getHighloadElements($this->iblocks[$code], false, true);
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
		private $iblock, $sections, $categories, $types, $brands, $products, $offers, $files, $artnumbers;

		private $counter = array('add'=>0, 'update'=>0, 'offers'=>0, 'error'=>0);

		private $together = array('92c85955-7fb6-11e4-aec5-0025908101de', '98c2eff7-7fb6-11e4-aec5-0025908101de'); // Общие разделы

		private $sale = "77ebb501-85d4-11e4-82e4-0025908101de";

		private $remove;

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("catalog");
			CModule::IncludeModule("highloadblock");

			$this->iblocks    = Import::getIBlocks();
			$dbHblock = HL\HighloadBlockTable::getList();
            while ($ib = $dbHblock->Fetch())
            	$this->iblocks[$ib['TABLE_NAME']] = (int)$ib['ID'];

            $remove = array();
			$this->remove = Import::getHighloadElements($this->iblocks['sizes'], true);
			foreach ($this->remove as $i)
				$remove[] = "/(\s(".$i['NAME'].")|_".strtolower(str_replace(array(',', '.'), '_', $i['NAME'])).")$/";
			$this->remove = $remove;
			$this->artnumbers = Import::getAllArtnumbers($this->iblocks['products']);
			$this->sections   = Import::getIBlockSections($this->iblocks['products'], 3);
			$this->categories = Import::getHighloadElements($this->iblocks['categories'], true);
			$this->types      = Import::getHighloadElements($this->iblocks['types'], true);
			$this->brands     = Import::getHighloadElements($this->iblocks['brands'], true);
			$this->properties = Array("SORT", "PREVIEW_PICTURE", "DETAIL_TEXT", "PROPERTY_COLOR", "PROPERTY_SIZE", "PROPERTY_TRADELINE", "PROPERTY_MATERIAL", "PROPERTY_SALE", "PROPERTY_PICTURES", "PROPERTY_BRAND", "PROPERTY_SECTION_1", "PROPERTY_SECTION_2", "PROPERTY_SECTION_3", "PROPERTY_SECTION_4", "IBLOCK_SECTION", "PROPERTY_CODE", "PROPERTY_ARTNUMBER", "PROPERTY_NOTE_SHORT", "PROPERTY_NOTE_FULL", "PROPERTY_NEW", "PROPERTY_COMING", "PROPERTY_PROMOTION", "PROPERTY_BEST" );
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
			if(isset($fields['PROPERTY_VALUES']['OFFER_SIZE'])):
				$offer = array(
						'NAME'            => $fields['NAME'],
						'CODE'            => $fields['CODE'],
						'XML_ID'          => $fields['XML_ID'],
						'PROPERTY_VALUES' => array(
								'SIZE'      => $fields['PROPERTY_VALUES']['OFFER_SIZE'],
								'ARTNUMBER' => $fields['PROPERTY_VALUES']['ARTNUMBER']
							)
				);
				$fields['NAME']   = preg_replace($this->remove, '', $fields['NAME']);
				$fields['CODE']   = preg_replace($this->remove, '', $fields['CODE']);
				$fields['XML_ID'] = $fields['CODE'];

				unset($fields['PROPERTY_VALUES']['CODE']);

				return $offer;
			else:
				return false;
			endif;
		}
		private function getExist($fields)
		{
			$array = array(
				'ID'             => $fields['ID'],
				'NAME'           => $fields['NAME'],
				'XML_ID'         => $fields['XML_ID'],
				'IBLOCK_SECTION' => $fields['IBLOCK_SECTION'],
			);
			foreach ($fields['PROPERTY_VALUES'] as $key => $prop):
				switch ($key):
					case 'NOTE_SHORT';
					case 'NOTE_FULL';
					case 'CODE':
					case 'BRAND':
					case (preg_match('/SECTION_(.*)/', $key) ? true : false):
					case 'MATERIAL':
					case 'COLOR':
					case 'SIZE':
					case 'SALE':
					case 'TRADELINE':
					case 'ARTNUMBER':
						$array[$key] = $prop;
						break;
				endswitch;
			endforeach;
			return $array;
		}
		private function getSections($propSections, &$fields, &$props)
		{
			$sections = array();

			if($propSections['section']):
				$value  = $propSections['section'];
				$parent = $this->sections[$value];
				$sections['first'] = array();
				if(isset($parent))
					$sections['first'][] = $value;
			endif;
			if($propSections['category']):
				$value  = $propSections['category'];
				unset($parent);
				if(!isset($sections['first'])):
					$sections['first'] = array($this->together[0], $this->together[1]);
				endif;
				foreach ($sections['first'] as $s):
					$parent = &$this->sections[$s];
					if(!isset($parent['CHILD'][$value]))
						$this->addParentCatagory($parent, $this->categories[$value]);
				endforeach;
				$sections['id']     = $this->categories[$value]['ID'];
				$sections['second'] = $value;
			elseif(isset($sections['first'][0])):
				$fields['IBLOCK_SECTION'][] = $this->sections[$sections['first'][0]];
				$fields['IBLOCK_SECTION'][] = $this->sections['all'];
				// Пометка промо-товаров
				if($sections['first'][0] == "d09c1ec1-09f9-11e5-a563-0025901865a2"):
					$props["PROMOTION"] = "Y";
					endif;
			endif;
			if($propSections['type']):
				$value  = $propSections['type'];

				$fields['IBLOCK_SECTION'] = array();
				foreach ($sections['first'] as $k => $s):
					unset($parent);
					$parent = &$this->sections[$s]['CHILD'][$sections['second']];
					if(!isset($parent['CHILD'][$value]))
						$this->addParentCatagory($parent, $this->types[$value]);
					if(isset($parent['CHILD'][$value]))
						$fields['IBLOCK_SECTION'][] = $parent['CHILD'][$value];
				endforeach;
				if(intval($sections['id'])>0)
					$props['SECTION_'.$sections['id']] = $value;
				$fields['IBLOCK_SECTION'][] = $this->sections['all'];
			endif;

			// Частные случаи разделов
			if($props["SALE"] != $this->sale && strlen($props["SALE"]) > 0):
				$fields['IBLOCK_SECTION'][] = $this->sections['sale30'];
			endif;
			if($props["SALE"] == $this->sale && strlen($props["SALE"]) > 0):
				$fields['IBLOCK_SECTION'][] = $this->sections['sale20'];
			endif;
			if(strlen($props["SALE"]) > 0):
				$fields['IBLOCK_SECTION'][] = $this->sections['sale'];
			endif;

			if($props["BEST"]=='Y'):
				$fields['IBLOCK_SECTION'][] = $this->sections['best-sellers'];
			endif;

			if($props["COMING"]=='Y'):
				$fields['IBLOCK_SECTION'][] = $this->sections['coming'];
			endif;
			if($props['NEW'] == "Y"):
				$fields['IBLOCK_SECTION'][] = $this->sections['latest'];
			endif;

			if(!in_array($props["ARTNUMBER"], $this->artnumbers) && $props["COMING"]!='Y'):
				$fields['IBLOCK_SECTION'][] = $this->sections['new'];
			endif;
		}
		private function getData($item)
		{
			$xml_id    = $item->getAttribute('id');
			$artnumber = $item->getElementsByTagName('artnumber')->item(0)->nodeValue;
			$tmp       = $item->getElementsByTagName('namePrint')->item(0)->nodeValue;
			$slug      = str_replace(array(' ','/', '.'), '_', preg_replace($this->remove, '', $item->getElementsByTagName('name')->item(0)->nodeValue));

			$fields = array(
				'XML_ID'      => $xml_id,
				'DETAIL_TEXT' => $item->getElementsByTagName('description')->item(0)->nodeValue
			);

			$props = array(
				'CODE'       => intval($item->getAttribute('code')),
				'ARTNUMBER'  => $artnumber,
				'NOTE_FULL'  => preg_replace($this->remove, '', $tmp),
				'COLOR'      => array(),
				'MATERIAL'   => array(),
				'PICTURES'   => array()
			);

			$raw = $item->getElementsByTagName('property');

			$propSections = array();

			foreach ($raw as $prop):
				$id    = $prop->getAttribute('id');
				$value = $prop->getAttribute('value');

				switch ($id):
					case 'material':
					case 'color':
						$props[strtoupper($id)][] = $value;
					break;
					case 'SizeForWeb':
						if(strlen($value) > 0)
							$props['SIZE'] = $value;
					break;
					case 'size':
						$props['OFFER_SIZE'] = $value;
					break;
					case 'sale':
					case 'tradeline':
					case 'brand':
					case 'new':
					case 'coming':
						$props[strtoupper($id)] = $value;
						break;
					case 'best-sellers':
						$props["BEST"] = $value;
						break;
					case 'comming':
						$props[strtoupper('coming')] = $value;
						break;
					case 'section':
					case 'category':
					case 'type':
						$propSections[$id] = $value;
				endswitch;
			endforeach;

			$this->getSections($propSections, $fields, $props);


			if(mb_strpos($tmp, $artnumber) === 0):
				$note  = mb_ucfirst(str_replace($item->getElementsByTagName('name')->item(0)->nodeValue." ", "", $tmp));
			elseif(strlen($artnumber) == 0):
				if(isset($propSections['category'])):
					$array = preg_split('/\s+/', $tmp);
					$note = $array[0]." ".$array[1];
				else:
					$note = $tmp;
				endif;
			elseif(mb_strpos($tmp, $artnumber)):
				$note  = mb_substr($tmp, 0, mb_strpos($tmp, $artnumber)-1);
			else:
				$note = $tmp;
			endif;

			$props['NOTE_SHORT'] = $note;
			$name = $note;

			if($this->brands[$props['BRAND']]['NAME']):
				$name .= ' '.$this->brands[$props['BRAND']]['NAME'];
				if(strlen($artnumber) > 0) $name .= ' '.str_replace($artnumber.' ','', $item->getElementsByTagName('name')->item(0)->nodeValue);
			endif;
			if(strlen($name) == 0):
				$name = $item->getElementsByTagName('name')->item(0)->nodeValue;
			endif;

			$fields["NAME"] = str_replace(array("Оо","Уу", "Ee"), array("То","Бу", "Рe"), $name);
			$fields["CODE"] = Translit::UrlTranslit((strlen($note)>0?$note." ":""). str_replace(array("Оо","Уу", "Ee"), array("То","Бу", "Рe"),$item->getElementsByTagName('name')->item(0)->nodeValue));


			$images = array_merge(glob($_SERVER['DOCUMENT_ROOT']."/import/photos/".$slug.".jpg"), glob($_SERVER['DOCUMENT_ROOT']."/import/photos/".$slug."_[0-9].jpg"));

			foreach ($images as $key=>$image):
				if($key==0)
					$fields['PREVIEW_PICTURE'] = CFile::MakeFileArray($image);
				$props['PICTURES']['n'.$key] = array("VALUE"=>CFile::MakeFileArray($image));
			endforeach;

			if(!isset($fields['PREVIEW_PICTURE'])):
				$fields['SORT'] = 60000000;
			else:
				$fields['SORT'] = (count($this->categories)+1-$this->categories[$propSections['category']]['ID'])*100;
			endif;

			$fields["PROPERTY_VALUES"] = $props;
			return $fields;
		}
		private function getFiles($items)
		{
			$ids = array();
			foreach ($items as $item):
				$fields = $this->getData($item);
				$offer  = $this->getOffer($fields);
				$props  = &$fields["PROPERTY_VALUES"];
				$exist  = $this->checkExist($fields);
				if(isset($exist)):
					if(isset($exist['PICTURES'])) $ids = array_merge($ids, $exist['PICTURES']);
				endif;
			endforeach;
			$res = CFile::GetList(array("FILE_SIZE"=>"desc"), array("@ID"=> implode(',', $ids)));
			while($file = $res->GetNext())
				$this->files[$file['ID']] = array('TIME' => strtotime($file['TIMESTAMP_X']), 'SIZE'=>$file['FILE_SIZE'],
					'SRC'=> "/upload/" . $file["SUBDIR"] . "/" . $file["FILE_NAME"]);
		}
		private function checkExist($fields)
		{
			$exist = false;
			$exist = $this->products[$fields['XML_ID']];
			if(!$exist)
				$exist = $this->products[$this->offers[$fields['XML_ID']]['CML2_LINK_XML_ID']];
			if(!$exist)
				$exist = $this->products[$fields['CODE']];
			return $exist;
		}
		public function Action($file, $offset)
		{
			$ids     = array();
			$data    = Import::getElements($file, array('products', 'product'), $offset);

			foreach ($data['items'] as $item):
				$ids[] = $item->getAttribute('id');
				$ids[] = Translit::UrlTranslit(substr($item->getElementsByTagName('namePrint')->item(0)->nodeValue, 0, strpos($item->getElementsByTagName('namePrint')->item(0)->nodeValue, $item->getElementsByTagName('artnumber')->item(0)->nodeValue)-1)." ".preg_replace($this->remove, '', $item->getElementsByTagName('name')->item(0)->nodeValue));
			endforeach;

			$this->offers = Import::getIBlockElements($this->iblocks['offers'], array('XML_ID' => $ids), array('PROPERTY_SIZE', "PROPERTY_CML2_LINK", "PROPERTY_CML2_LINK.XML_ID"));

			foreach ($offers as $offer):
				if(in_array($offer['XML_ID'], $xml_ids))
					unset($xml_ids[array_search($offer['XML_ID'], $xml_ids)]);
				$ids[] = $offer['CML2_LINK_XML_ID'];
			endforeach;

			$this->products = Import::getIBlockElements($this->iblocks['products'], array('XML_ID' => $ids), $this->properties);

			$this->getFiles($data['items']); // Получаем список изображений с датой и размером

			foreach ($data['items'] as $item):

				$fields = $this->getData($item);
				$offer  = $this->getOffer($fields);
				$props  = &$fields["PROPERTY_VALUES"];

				$exist  = $this->checkExist($fields);

				if(isset($exist)):
					$update = false;
					$diff   = array_diff($props, $exist);

					// Проверка изображений
					if(count($props['PICTURES']) > 0):
						$updateImages = false;
						if(!isset($exist['PICTURES'])):
							$updateImages = true;
						else:
							if(!file_exists($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($exist['PREVIEW_PICTURE']))) $updateImages = true;
							foreach($exist['PICTURES'] as $imgKey => $img):
								if(filemtime($props['PICTURES']['n'.$imgKey]['VALUE']['tmp_name']) > $this->files[$img]['TIME']) $updateImages = true;
								if(!file_exists($_SERVER['DOCUMENT_ROOT'].$this->files[$img]['SRC'])) $updateImages = true;
							endforeach;
						endif;
						if($updateImages):
							$diff['PICTURES'] = $props['PICTURES'];
							$raw = new CIBlockElement;
							$raw->Update($exist['ID'], array('PREVIEW_PICTURE'=>$fields['PREVIEW_PICTURE']));
						endif;
					endif;

					// Проверка свойств
					foreach (array('SALE', 'NEW', 'COMING', 'BEST', 'PROMOTION') as $prop) {
						if(!isset($exist[$prop]) && strlen($props[$prop])>0):
							$diff[$prop] = $props[$prop];
						elseif(isset($exist[$prop]) && !isset($props[$prop])):
							$diff[$prop] = false;
						endif;
					}
					foreach (array('NOTE_SHORT', 'NOTE_FULL') as $prop) {
						if($props[$prop] != $exist[$prop]):
							$diff[$prop] = $props[$prop];
						endif;
					}
					unset($diff['OFFER_SIZE']);

					foreach (array('COLOR', 'MATERIAL') as $el):
						if(array_diff($props[$el], $exist[$el]) || (count($props[$el])!=count($exist[$el]))):
							$diff[$el] = $props[$el];
						else:
							unset($diff[$el]);
						endif;
					endforeach;

					if(count($diff)>0):
						fwrite(STDERR, "Что-то обновлено: ".$fields['XML_ID']." ".$exist['ID']." ".var_export($diff, true)." \n\r");
						CIBlockElement::SetPropertyValuesEx($exist['ID'], $this->iblocks['products'], $diff);
						$update = true;
					endif;

					foreach (array('SORT', 'NAME', 'DETAIL_TEXT') as $el):
						if($fields[$el] != $exist[$el]):
							$raw = new CIBlockElement;
							$raw->Update($exist['ID'], array($el => $fields[$el]));
							$update = true;
						endif;
					endforeach;

					if(array_diff($fields['IBLOCK_SECTION'], $exist['IBLOCK_SECTION']) || (count($fields['IBLOCK_SECTION'])!=count($exist['IBLOCK_SECTION']))):
						#fwrite(STDERR, "Разделы обновлены: ".var_export(array_diff($fields['IBLOCK_SECTION'], $exist['IBLOCK_SECTION']),true)." \n\r");
						$raw = new CIBlockElement;
						$raw->Update($exist['ID'], array('IBLOCK_SECTION'=>$fields['IBLOCK_SECTION']));
						$update = true;
					endif;

					if(isset($props['OFFER_SIZE'])):
						if(!isset($this->offers[$offer['XML_ID']])):
							$offer['PROPERTY_VALUES']['CML2_LINK'] = $exist['ID'];
							$id = Import::addIBlockElement($this->iblocks['offers'], $offer);
							if(intval($id)>0):
								CCatalogProduct::Add(array('ID'=>$id, 'QUANTITY'=>1));
								$this->counter['offers']++;
							else:
								$this->counter['error']++;
							endif;
						else:
							if($this->offers[$offer['XML_ID']]['SIZE'] != $offer['PROPERTY_VALUES']['SIZE']):
								fwrite(STDERR, "Размеры обновлены \n\r");
								$update = true;
								CIBlockElement::SetPropertyValuesEx($this->offers[$offer['XML_ID']]['ID'], $this->iblocks['offers'], array('SIZE'=>$offer['PROPERTY_VALUES']['SIZE']));
							endif;
						endif;
					endif;

					if($update):
						$this->counter['update']++;
					endif;

				else:
					$id = Import::addIBlockElement($this->iblocks['products'], $fields);
					if(intval($id)>0):
						CCatalogProduct::Add(array('ID'=>$id, 'QUANTITY'=>1));
						$this->counter['add']++;
						$fields['ID'] = $id;
						$this->products[$fields['XML_ID']] = $this->getExist($fields);
						if($offer):
							$offer['PROPERTY_VALUES']['CML2_LINK'] = $id;
							$id = Import::addIBlockElement($this->iblocks['offers'], $offer);
							if(intval($id)>0):
								CCatalogProduct::Add(array('ID'=>$id, 'QUANTITY'=>1));
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

	class Counts
	{
		private $iblock, $stores, $products, $counts;
		private $counter = 0;

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("catalog");

			$this->iblocks = Import::getIBlocks();

			$raw = CCatalogStore::GetList(array('ID'=>'ASC'), array('ACTIVE' => 'Y'));
        	while ($item = $raw->Fetch())
        		$this->stores[$item['XML_ID']] = $item['ID'];

		}

		public function Action($file, $offset)
		{

			if ($offset == 1 && strstr($file, 'retail')):
				$all = Import::getIBlockElements($this->iblocks['products'], array('ACTIVE' => "Y", "PROPERTY_SET" => false), array('ID'));
				if(count($all) > 0):
					foreach ($all as $item):
						$raw = new CIBlockElement;
						$raw->Update($item['ID'], array('ACTIVE'=>'N'));
					endforeach;
					return 1;
				endif;
			endif;

			$data = Import::getElements($file, array('counts', 'product'), $offset);
			$ids  = array();
			foreach ($data['items'] as $item)
				$ids[] = $item->getAttribute('id');

			$this->products = array_merge(Import::getIBlockElements($this->iblocks['products'], array('XML_ID' => $ids), array('ID', 'ACTIVE', 'PROPERTY_GENERAL', 'PROPERTY_RETAIL', 'PROPERTY_SHOWCASE', 'PROPERTY_COMING')), Import::getIBlockElements($this->iblocks['offers'], array('XML_ID' => $ids), array('ID', 'ACTIVE', 'PROPERTY_CML2_LINK')));

			$ids  = array();
			$cmlIDs = array();
			foreach ($this->products as $item) {
				if(isset($item['CML2_LINK'])) $cmlIDs[] = $item['CML2_LINK'];
				$ids[$item['XML_ID']] = $item['ID'];
			}
			if(count($cmlIDs) > 0) {
				$raw = Import::getIBlockElements($this->iblocks['products'], array('ID' => $cmlIDs), array('ID', 'ACTIVE', 'PROPERTY_COMING'));
				foreach($raw as $item) {
					$this->products[$item['ID']] = $item;
				}
			}

			$raw = CCatalogStoreProduct::GetList(array('ID'=>'ASC'), array('ACTIVE' => 'Y', 'PRODUCT_ID'=>array_values($ids)));
        	while ($count = $raw->Fetch()):
        		if(!isset($this->counts[$count['PRODUCT_ID']]))
        			$this->counts[$count['PRODUCT_ID']] = array();
        		$this->counts[$count['PRODUCT_ID']][$count['STORE_ID']] = $count['AMOUNT'];
        	endwhile;

        	foreach ($data['items'] as $item):
				$product  = &$this->products[$item->getAttribute('id')];
				$id       = $product['ID'];
				$raw      = $item->getElementsByTagName('count');
				$updateID = (isset($product['CML2_LINK'])?$product['CML2_LINK']:$id);
				$arData   = array();
				if(intval($id) > 0):
					foreach ($raw as $count):
						$amount = $count->getAttribute('value');
						if(!$this->stores[$count->getAttribute('store')]):
							$this->stores[$count->getAttribute('store')] = CCatalogStore::Add(array('TITLE'=>$count->getAttribute('description'), 'XML_ID'=>$count->getAttribute('store')));
						endif;

						$store = $this->stores[$count->getAttribute('store')];

						$arData[$count->getAttribute('store')] = $amount;

						$coming = false;
						if($product['COMING'] == 'Y') $coming = true;
						if($this->products[$product['CML2_LINK']]['COMING'] == 'Y') $coming = true;

						// Активация товаров которые в наличии
						if((intval($amount) > 0 || $coming) && (isset($product['CML2_LINK']) || $product['ACTIVE'] == 'N')):
				    		$raw = new CIBlockElement;
				    		if(isset($product['CML2_LINK'])):
				    			$raw->Update($product['CML2_LINK'], array('ACTIVE'=>'Y'));
				    		else:
				    			$product['ACTIVE'] = "Y";
				    			$raw->Update($id, array('ACTIVE'=>'Y'));
				    		endif;

				    		#$this->counter++;
				    	endif;

				    	if($this->counts[$id][$store] != $amount):
				    		if(!isset($this->counts[$id]))
								$this->counts[$id] = array();
							$this->counts[$id][$store] = $amount;

				    		$arFields = Array(
								"PRODUCT_ID" => $id,
								"STORE_ID"   => $store,
								"AMOUNT"     => $amount,
						    );
						    $ID = CCatalogStoreProduct::UpdateFromForm($arFields);
						    if($ID > 0) {
						    	$this->counter++;
						    }
						endif;


	        		endforeach;

	        		// Товары на основном или розничном складе
	        		$arUpdates = array();
	        		foreach(array(0, 1) as $store):
	        			if(isset($arData[$store])):
	        				$arUpdates[] = array('FIELD' => "GENERAL", 'AMOUNT' => $arData[$store]);
	        				// Витринный экземпляр
	        				if($store == 0):
	        					$updateShowcase = false;
	        					if($arData[$store] == 1 && $product['SHOWCASE'] != 'Y'):
	        						$updateShowcase = "Y";
	        					elseif($arData[$store] != 1 && $product['SHOWCASE'] != 'N'):
	        						$updateShowcase = "N";
	        					endif;
	        					if($updateShowcase):
	        						CIBlockElement::SetPropertyValuesEx($updateID, $this->iblocks['products'], array('SHOWCASE'=>$updateShowcase));
	        					endif;
	        				endif;
	        				unset($arData[$store]);
	        			endif;
	        		endforeach;

	        		if(count($arData) > 0):
	        			$arUpdates[] = array('FIELD' => "RETAIL", 'AMOUNT' => array_sum($arData) );
					endif;

					foreach ($arUpdates as $v) {
						unset($updateValue);
						if(intval($v['AMOUNT']) > 0 && $product[$v['FIELD']] != "Y"):
				    		$updateValue = "Y";
				    		$product[$v['FIELD']] = "Y";
				    	elseif(intval($v['AMOUNT']) == 0 && (!isset($product[$v['FIELD']]) || $product[$v['FIELD']] == "Y")):
				    		$updateValue = "N";
				    	endif;
				    	if(isset($updateValue)):
				    		CIBlockElement::SetPropertyValuesEx($updateID, $this->iblocks['products'], array($v['FIELD']=>$updateValue));
				    		$this->counter++;
				    	endif;
					}


	        	endif;
        	endforeach;

        	fwrite(STDERR, "\033[35m Update: ".$this->counter." \033[37m\r\n");

			if($data['offset'] != 'end')
				return $data['offset'];
			else
				return 0;
		}
	}

	class Prices
	{
		private $iblock, $products, $prices, $types;
		private $counter = array('add'=>0, 'update'=>0);

		public function __construct()
		{
			CModule::IncludeModule("iblock");
			CModule::IncludeModule("catalog");

			$this->iblocks = Import::getIBlocks();

			$raw = CCatalogGroup::GetList(array("SORT" => "ASC"), array());
			while ($type = $raw->Fetch())
				$this->types[$type['ID']] = $type['NAME'];
		}

		public function Action($file, $offset)
		{
			$data = Import::getElements($file, array('prices', 'product'), $offset);
			$ids  = array();

			foreach ($data['items'] as $item)
				$ids[] = $item->getAttribute('id');

			$this->products = array_merge(Import::getIBlockElements($this->iblocks['products'], array('XML_ID' => $ids), array('ID', 'PROPERTY_MIN_PRICE')), Import::getIBlockElements($this->iblocks['offers'], array('XML_ID' => $ids), array('ID', 'PROPERTY_CML2_LINK','PROPERTY_CML2_LINK.PROPERTY_MIN_PRICE')));

			$ids  = array();
			foreach ($this->products as $item)
				$ids[$item['XML_ID']] = $item['ID'];

			$raw = CPrice::GetList(array(), array("PRODUCT_ID" => array_values($ids)));
        	while ($price = $raw->Fetch()):
        		if(!isset($this->prices[$price['PRODUCT_ID']]))
        			$this->prices[$price['PRODUCT_ID']] = array();
        		$this->prices[$price['PRODUCT_ID']][$this->types[$price['CATALOG_GROUP_ID']]] = array('id'=> $price['ID'],'price'=>$price['PRICE']);
        	endwhile;
        	foreach ($data['items'] as $item):
        		$id     = $this->products[$item->getAttribute('id')]['ID'];
        		if(intval($id) > 0):
	        		$prices = array('RETAIL'=>$item->getElementsByTagName('retail')->item(0)->nodeValue, 'WHOLESALE'=>$item->getElementsByTagName('wholesale')->item(0)->nodeValue);
	        		if(!isset($this->prices[$id])):
	        			foreach ($prices as $key => $price):
	        				$arFields = Array(
								"PRODUCT_ID"       => $id,
								"CATALOG_GROUP_ID" => array_search($key, $this->types),
								"PRICE"            => $price,
								"CURRENCY"         => "RUB"
							);
	        				CPrice::Add($arFields);
							$this->prices[$id][$key]['price'] = $price;
	        				$this->counter['add']++;
	        			endforeach;
	        		else:
	        			foreach ($prices as $key => $price):
	        				if(intval($this->prices[$id][$key]['price']) != intval($price)):
	        					$arFields = Array(
									"PRODUCT_ID"       => $id,
									"CATALOG_GROUP_ID" => array_search($key, $this->types),
									"PRICE"            => $price,
									"CURRENCY"         => "RUB"
								);
	        					CPrice::Update($this->prices[$id][$key]['id'], $arFields);
	        					$this->counter['update']++;
	        				endif;
	        			endforeach;
	        		endif;
	        		$exist = $this->products[$item->getAttribute('id')];
	        		$price = intval($item->getElementsByTagName('retail')->item(0)->nodeValue);
	        		if($exist):
	        			if(isset($exist['CML2_LINK'])):
	        				if(isset($exist['CML2_LINK_MIN_PRICE'])):
	        					if($exist['CML2_LINK_MIN_PRICE'] != $price):
									$new = $price;
								endif;
							else:
								$new = $price;
	        				endif;
	        				if($new):
	        					$id = $exist['CML2_LINK'];
	        				endif;
	        			else:
	        				if(intval($exist['MIN_PRICE']) != $price):
								$new = $price;
								$id = $exist['ID'];
							endif;
	        			endif;
	        			if($new):
	        				CIBlockElement::SetPropertyValuesEx($id, $this->iblocks['products'], array('MIN_PRICE'=>$new));
	        				$this->counter['update']++;
	        			endif;
	        		endif;
	        	endif;
        	endforeach;

        	fwrite(STDERR, "\033[35m Update: ".$this->counter['update']." \033[32m Add: ".$this->counter['add']." \033[37m\r\n");

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

		private $checkfile = true;

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
				if(file_exists($this->lock)) {
					if(time() - filemtime($this->lock) < 60 * 15) {
						return;
					}
				} else {
					file_put_contents($this->lock, '');
				}
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
