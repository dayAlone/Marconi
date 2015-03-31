<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
if(!isset($_REQUEST['short'])):
	$APPLICATION->SetPageProperty('body_class', "contacts");
	$APPLICATION->SetTitle("Contacts");
	?>	<div class="page">
	    <div class="container">
	    	<div class="row xl-margin-top">
	    		<div class="col-md-12">
	    			<div class="page__title page__title--full-width">Contacts</div>
	    			<div class="row">
	    				<div class="col-xs-6">
	    					<p>
			    				125212 Moscow, Russia<br>
								Admiral Makarov Street 8, str. 1<br>
			    			</p>
	    				</div>
	    				<div class="col-xs-6">
	    					<p>
								+7 495 7872264 <br>
								E-mail: <a href="mailto:info@fmarconi.ru">info@fmarconi.ru</a> <br>
							</p>
	    				</div>
	    			</div>
	    			
	    		</div>
	    	</div>
		</div>
	</div>
	<?
endif;
?>
<div id="contactsMap" data-lang="en"></div>
<?

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>