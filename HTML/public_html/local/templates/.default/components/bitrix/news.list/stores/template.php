<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS'])>0): 
	if(isset($_REQUEST[$arParams["FIELD_NAME"]])):
		foreach ($arResult['ITEMS'] as $item):
			if($item['VALUE']==$_REQUEST[$arParams["FIELD_NAME"]])
				$name = $item['NAME'];
		endforeach;
	endif;
?>
<div class="stores-list">
	<? if(count($arResult['ITEMS'])>1): ?>
	<div class="dropdown">
		<a href="#" class="dropdown__trigger"><span class="dropdown__text"><?=(strlen($name)>0?$name:"Выберите пункт самовывоза")?></span><?=svg('arrow')?></a>
		<span class="dropdown__frame">
			<a href="#" data-id="" class="dropdown__item">Выберите пункт самовывоза</a>				
			<?
			foreach ($arResult['ITEMS'] as $item):
				?><a href="#" class="dropdown__item" data-id="<?=$item['ID']?>"><?=$item['NAME']?></a><?
			endforeach;
			?>
		</span>
		<select class="dropdown__select" name="<?=$arParams["FIELD_NAME"]?>" required>
	        <option value="">Выберите пункт самовывоза</option>
	        <?
			foreach ($arResult['ITEMS'] as $item):
				?><option data-id="<?=$item['ID']?>" value="<?=$item['VALUE']?>" <?=($item['VALUE']==$_REQUEST[$arParams["FIELD_NAME"]]?"selected":"")?>><?=$item['NAME']?></option><?
			endforeach;
			?>
	      </select>
	</div>
	<?else:?>
		<input type="hidden" name="<?=$arParams["FIELD_NAME"]?>" value="<?=$arResult['ITEMS'][0]['VALUE']?>">
	<?endif;?>
	<div class="stores-list__description <?=(count($arResult['ITEMS'])==1?"stores-list__description--active":"")?>">
		<small><strong>адрес пункта самовывоза</strong></small><br>
		<?foreach ($arResult['ITEMS'] as $key => $item):?>
			<div data-id="<?=$item['ID']?>" class="stores-list__item <?=($key==0?"stores-list__item--active":"")?>">
				<?=html_entity_decode($item['PROPERTIES']['ADDRESS']['VALUE']['TEXT'])?>
			</div>
		<?endforeach;?>
	</div>
</div>
<?endif;?>