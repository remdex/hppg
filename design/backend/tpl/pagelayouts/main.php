<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>
<body>
<div id="container">

    <div id="main-header-bg">
       
            <div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1></div>
       
        
        <div class="top-menu float-break">
        	<ul>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('system/configuration')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Configuration');?></a></li>    	   
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/admincategorys')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Gallery categorys');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbumsadmin')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads to albums');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/batchadd')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Batch images add');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/duplicates')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Duplicates');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('article/staticlist')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Static articles');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('statistic/index')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Statistic');?></a></li>
        	   <li><a href="<?=erLhcoreClassDesign::baseurl('shop/index')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Shop');?></a></li>
        	</ul>
        </div>
        
    </div>

    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>
    
	<div id="bodcont" class="float-break">	
	
		<div id="leftmenucont">
		      <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_admin.tpl.php'));?>		      
		</div>

		
				
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
    			
    			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/go_to_album_box.tpl.php'));?>	
    				
    			<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/clear_cache_box.tpl.php'));?>		
    			
    									
		    </div>	
		</div>
		
	</div>	
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
</div>
</body>
</html>