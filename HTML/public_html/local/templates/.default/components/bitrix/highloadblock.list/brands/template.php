<?
	$this->setFrameMode(true);
	$arResult['TRADELINES'] = getHighloadElements('tradeline', 'UF_XML_ID', 'UF_NAME');
	if(count($arResult['rows'])):
		$brand = (isset($_COOKIE['BRAND'])?$_COOKIE['BRAND']:(isset($_REQUEST['brand'])?$_REQUEST['brand']:""));
		if(strlen($brand)>0):
			$all = $arResult['TRADELINES'];
			foreach ($arResult['rows'] as $item)
				$all[$item['UF_XML_ID']] = $item['UF_NAME'];
			foreach ($all as $key => $item):
				if($brand==$key):
					$current =  $item;
					$this->setFrameMode(false);
				endif;
			endforeach;
		endif;
		?>
		<div class="brand-select">
			<div class="dropdown">
				<a href="#" class="dropdown__trigger"><span class="dropdown__text"><?=(isset($current)?$current:"Все бренды")?></span><?=svg('arrow')?></a>
				<span class="dropdown__frame">
					<ul>
					<li><a href="#" <?=(!isset($current)?'style="display:none"':'')?> data-id="" class="dropdown__item">Все бренды</a></li>
					<?
					foreach ($arResult['rows'] as $item):
						?><li><a href="#" class="dropdown__item" <?=($current == $item['UF_XML_ID']?'style="display:none"':'')?> data-id="<?=$item['UF_XML_ID']?>"><?=$item['UF_NAME']?>
						<?
							if($item['UF_XML_ID']=="4d20dff1-7fb6-11e4-aec5-0025908101de"):
								?>
								<?=svg('arrow')?>
								</a>
								<ul>
									<?foreach ($arResult['TRADELINES'] as $key => $item):?>
										<li><a href="#" class="dropdown__item" data-id="<?=$key?>"><?=$item?></a>
									<?endforeach;?>
								</ul>
								<?
							else:?></a><?
							endif;
						?></li><?
					endforeach;
					?>
					</ul>
				</span>
				<select class="dropdown__select">
	                <option value="0" data-id="">Все бренды</option>
	                <?
					foreach ($arResult['rows'] as $item):
						?><option value="<?=$item['UF_XML_ID']?>" data-id="<?=$item['UF_XML_ID']?>" <?=($current == $item['UF_NAME']?'selected':'')?>><?=$item['UF_NAME']?></option><?
					endforeach;
					?>
	              </select>
			</div>
		</div>
		<?
	endif;
?>