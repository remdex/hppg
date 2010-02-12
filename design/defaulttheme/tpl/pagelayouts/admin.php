<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head_admin.tpl.php'));?>

</head>
<body>

<div id="container">

<div id="main-header-bg"><div id="topcontainer">
<div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1>
</div></div>

	
	<div class="clearer"></div>
</div>
<div class="top-menu">

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
										
		    </div>	
		</div>
		
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
<br />

</div>
</body>

</html>