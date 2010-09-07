<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>
<body>

<div id="container" class="no-right-column">

<div id="main-header-bg" class="float-break">
    <div id="logo">
        <a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><img src="<?=erLhcoreClassDesign::design('images/newdesign/logo.jpg')?>" alt="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" /></a>
    </div>
    
    <div class="top-menu float-break">        
    <?if (isset($Result['rss'])) : ?>
    <a class="rss-page" href="<?=$Result['rss']['url'];?>" title="<?=$Result['rss']['title']?>"></a>
    <? else :?>
    <a class="rss-page" href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploadsrss/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploaded images')?>"></a>
    <?endif;?>
    
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_top_menu.tpl.php'));?>
    </div>
    
    <div class="top-submenu">
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/search_box.tpl.php'));?>
    </div>
    
</div>




<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>

	<div id="bodcont" class="float-break">
		
	
				
        <div id="leftmenucont">
              
            <?php if (erLhcoreClassUser::instance()->isLogged()) : ?>
            <div class="left-infobox">
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_user.tpl.php'));?>	
            </div>
            <?php endif;?>
                                     
	  		 <div class="left-infobox">				
					<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Categories')?></h3>
					<ul>													
					    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/popular/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular images');?></a></li>                  
                        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploads/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?></a></li>                  
                        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/toprated/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a></li>                  
                        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lasthits/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?> </a></li>                  
                        <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastcommented/')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?></a></li>                  
					</ul>									
             </div>
                               
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_last_searchers.tpl.php'));?>
                         
            <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_last_hits.tpl.php'));?>	 
               	      
        </div>
        
        	
		<div id="middcont">
			<div id="mainartcont">
			
			<?
			 echo $Result['content'];		
			?>			
			
			</div>
		</div>
						
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>

</div>
</body>

</html>