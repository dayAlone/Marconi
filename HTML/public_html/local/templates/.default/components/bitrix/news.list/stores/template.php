<? $this->setFrameMode(true);?>
<? if(count($arResult['ITEMS'])>0): ?>
<div class="stores-list">
	<div class="dropdown">
		<a href="#" class="dropdown__trigger"><span class="dropdown__text">Выберите пункт самовывоза</span><?=svg('arrow')?></a>
		<span class="dropdown__frame">
			<a href="#" data-id="" class="dropdown__item">Выберите пункт самовывоза</a>				
			<?
			foreach ($arResult['ITEMS'] as $item):
				?><a href="#" class="dropdown__item" data-id="<?=$item['PROPERTIES']['STORE']['VALUE']?>"><?=$item['NAME']?></a><?
			endforeach;
			?>
		</span>
		<select class="dropdown__select" name="BUYER_STORE">
	        <option value="">Выберите пункт самовывоза</option>
	        <?
			foreach ($arResult['ITEMS'] as $item):
				?><option value="<?=$item['PROPERTIES']['STORE']['VALUE']?>" <?=($item['PROPERTIES']['STORE']['VALUE']==$arParams['CACHE_NOTE']?"selected":"")?>><?=$item['NAME']?></option><?
			endforeach;
			?>
	      </select>
	</div>
</div>
<? endif; ?>