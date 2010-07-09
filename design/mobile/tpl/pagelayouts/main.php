<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>


</head>
<body>

<div id="container">

<div id="main-header-bg"><div id="topcontainer">
<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
<div class="search-box" style="float:right;">
    <form action="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>"><input type="text" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?>" id="searchtext" onfocus="Javascript: if ( $('input#searchtext').val() == '<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search...')?>') { $('input#searchtext').val('');}" onblur="Javascript: if ($('input#searchtext').val() == '') { $('input#searchtext').val('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search...')?>'); }" class="keywordField" name="SearchText" value="<?=isset($Result['keyword']) ? htmlspecialchars($Result['keyword']) : erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search...')?>" /> <input type="submit" class="default-button" name="doSearch" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','ok')?>"/></form>
</div>
<?php endif; ?>


<div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1></div>



</div>
	
	<div class="clearer"></div>
</div>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_top_menu.tpl.php'));?>

	<div id="bodcont" class="float-break">
		<? if (isset($Result['path'])) : 
		
		$pathElementCount = count($Result['path'])-1;
		?>			
    		<div id="path">
    		  <a href="/"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?> &raquo;</a>
    		  
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
		
		<?php /*
		<div id="rightmenucont">		
			<div id="rightpadding">	
					<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
					<div class="right-infobox">
    					<div class="last-search-infobox">
        					<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last searches')?></h3>
        					<ul>
        					<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
        					   <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">&raquo; <?=htmlspecialchars($search->keyword);?> (<?=$search->countresult;?>)</a></li>
        					<?endforeach;?>
        					</ul>
    					</div>					
                    </div>	
                    <?php endif; ?>
                                                         
                    <div class="right-infobox">
                    <div class="last-search-infobox">
                        <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last viewed images')?></h3>
                        <?php 
                        $cache = CSCacheAPC::getMem(); 
                        $cacheVersion = $cache->getCacheVersion('last_hits_version',time(),600);
                        if (($Result = $cache->restore(md5($cacheVersion.'_lasthits_infobox'))) === false)
                        {
                            $items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'sort' => 'mtime DESC, pid DESC','offset' => 0, 'limit' => 2));
                            $appendImageMode = '/(mode)/lasthits';
                            $Result = '<ul class="last-hits-infobox">';                                                        
                            foreach ($items as $item)
                            {      
                               $Result .= '<li><a href="'.$item->url_path.$appendImageMode.'"><img title="'.erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','View image').'" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename),true,$item->pid).'" alt="'.htmlspecialchars($item->name_user).'" /></a></li>';
                            }                            
                            $Result .= '</ul>';
                            
                            $cache->store(md5($cacheVersion.'_lasthits_infobox'),$Result);
                          
                        }
                        echo $Result;
                        ?>
                    </div>	                      							
            		</div>								
		    </div>	
		</div>
		*/?>
		
		
		
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
<br />

</div>
</body>

</html>