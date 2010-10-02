<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>
<body>
<div id="container">

    <div id="main-header-bg">
        <div id="topcontainer">
            <div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1></div>
        </div>
    </div>

    <div class="top-menu float-break">
    	<ul>
    	   <li><a href="<?=erLhcoreClassDesign::baseurl('system/configuration')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Configuration');?></a></li>    	   
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

		<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>
				
		<div id="middcont">
			<div id="mainartcont">			
			<?
			     echo $Result['content'];		
			?>	
			</div>
		</div>
		
		<div id="rightmenucont">		
			<div id="rightpadding">									
    			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/user_box.tpl.php'));?>	
    			    				
    			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/file_delete_box.tpl.php'));?>	
    				
    			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/clear_cache_box.tpl.php'));?>		
    			
    									
		    </div>	
		</div>
		
	</div>	
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
</div>
</body>
</html>