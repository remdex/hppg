<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>

</head>
<body>

<div id="container">

<div id="main-header-bg"><div id="topcontainer">
<div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1>
</div></div>

	
	<div class="clearer"></div>
</div>
<div class="top-menu float-break">
	<ul>
			<li><a href="<?=erLhcoreClassDesign::baseurl('system/configuration')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Configuration');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('system/expirecache')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Clean cache');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Gallery categorys');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/batchadd')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Batch images add');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/duplicates')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Duplicates');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('article/staticlist')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Static articles');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('statistic/index')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Statistic');?></a></li>
			<li><a href="<?=erLhcoreClassDesign::baseurl('shop/index')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Shop');?></a></li>
	</ul>
</div>
	<div id="bodcont" class="float-break">	
	
		<div id="leftmenucont">
		      <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_admin.tpl.php'));?>		      
		</div>

		<? if (isset($Result['path'])) : 
		
		$pathElementCount = count($Result['path'])-1;
		?>			
    		<div id="path">
    		  <? foreach ($Result['path'] as $key => $pathItem) : ?>
    		      <? 
    		      $pathElementRaquo = ($key != $pathElementCount) ? '&raquo;' : '';
    		      if (isset($pathItem['url'])) { ?>
    		             <a href="<?=$pathItem['url']?>"><?=$pathItem['title']?> <?=$pathElementRaquo;?> </a>		      
    		      <? } else { ?>
    		      		 <?=$pathItem['title']?> <?=$pathElementRaquo;?>    
    		      <? }; ?>
    		  <? endforeach; ?>
    		</div>
		<? endif; ?>
				
		<div id="middcont">
			<div id="mainartcont">
			 <div style="padding:2px">
			<?
			 echo $Result['content'];		
			?>			
			</div>
			</div>
		</div>
		
		<div id="rightmenucont">
		
			<div id="rightpadding">
									
					<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/user_box.tpl.php'));?>
						
					<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Delete photo by ID')?></legend> 
					<input type="text" class="default-input" id="PhotoQuickDelete" value="">
					<input type="button" class="default-button" onclick="return hw.deletePhotoQuick($('#PhotoQuickDelete').val(),'<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Photo was deleted')?>')" value="OK" />
					</fieldset>
									
		    </div>	
		</div>
		
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
<br />

</div>
</body>

</html>