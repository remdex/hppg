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
        <a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><img src="<?=erLhcoreClassDesign::design('images/newdesign/logo.jpg')?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" alt="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" /></a>
    </div>
    
    <div class="top-menu float-break">        
    <?if (isset($Result['rss'])) : ?>
    <a class="rss-page" href="<?=$Result['rss']['url'];?>" title="<?=$Result['rss']['title']?>" /></a>
    <? else :?>
    <a class="rss-page" href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploadsrss/')?>" title="Last uploaded images" /></a>
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
           
        
             <?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
	  		 <div class="left-infobox search-infobox">				
					<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last searches')?></h3>
					<ul>
					<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
					   <li>
					   <a class="cnt" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">(<?=$search->countresult;?>)</a>	   
					   <a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>"><?=htmlspecialchars($search->keyword);?></a>
					   </li>
					<?endforeach;?>
					</ul>									
             </div>
             <?php endif; ?>
            	 
             <div class="left-infobox">                    
                <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last viewed images')?></h3>
                <?php 
                $cache = CSCacheAPC::getMem(); 
                $cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);
                if (($ResultCache = $cache->restore(md5($cacheVersion.'_lasthits_infobox'))) === false)
                {
                    $items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'sort' => 'mtime DESC, pid DESC','offset' => 0, 'limit' => 2));
                    $appendImageMode = '/(mode)/lasthits';
                    $ResultCache = '<ul class="last-hits-infobox">';                                                        
                    foreach ($items as $item)
                    {      
                       $ResultCache .= '<li><a href="'.$item->url_path.$appendImageMode.'"><img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','View image').'" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid).'" alt="'.htmlspecialchars($item->name_user).'" /></a></li>';
                    }                            
                    $ResultCache .= '</ul>';
                    
                    $cache->store(md5($cacheVersion.'_lasthits_infobox'),$ResultCache);
                  
                }
                echo $ResultCache;
                ?>                                         							
            </div>
                  
                 	      
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
   
                    							
		    </div>	
		</div>
		
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>

</div>
</body>

</html>