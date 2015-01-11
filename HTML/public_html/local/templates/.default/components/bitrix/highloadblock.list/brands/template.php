<?
	$this->setFrameMode(true);
	if(count($arResult['rows'])):
		if(isset($_COOKIE['BRAND'])):
			foreach ($arResult['rows'] as $item):
				if($_COOKIE['BRAND']==$item['UF_XML_ID']):
					$current =  $item;
					\Bitrix\Main\Data\StaticHtmlCache::getInstance()->markNonCacheable();
				endif;
			endforeach;
		endif;
		?>
		<div class="brand-select">
			<div class="dropdown">
				<a href="#" class="dropdown__trigger"><span class="dropdown__text"><?=(isset($current)?$current['UF_NAME']:"Все бренды")?></span><?=svg('arrow')?></a>
				<span class="dropdown__frame">
					<a href="#" <?=(!isset($current)?'style="display:none"':'')?> data-id="" class="dropdown__item">Все бренды</a>				
					<?
					foreach ($arResult['rows'] as $item):
						?><a href="#" class="dropdown__item" <?=($current == $item['UF_XML_ID']?'style="display:none"':'')?> data-id="<?=$item['UF_XML_ID']?>"><?=$item['UF_NAME']?></a><?
					endforeach;
					?>
				</span>
				<select class="dropdown__select">
	                <option value="">Все бренды</option>
	                <?
					foreach ($arResult['rows'] as $item):
						?><option value="<?=$item['UF_XML_ID']?>"><?=$item['UF_NAME']?></option><?
					endforeach;
					?>
	              </select>
			</div>
		</div>
		<?
	endif;
?>