<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>

<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/swfupload.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.swfobject.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.cookies.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.queue.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/swfupload.speed.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/fileprogress.js');?>"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/swfupload/plugins/handlers.js');?>"></script>


</head>
<body>

<div id="container" class="columns-3-site">

<div id="main-header-bg"><div id="topcontainer">
<div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></a></h1></div>

<div class="title-gallery">
<h1><?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></h1>
</div>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/search_box.tpl.php'));?>

</div>


	
	<div class="clearer"></div>
</div>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_top_menu.tpl.php'));?>

	<? if (isset($Result['path'])) : 		
	$pathElementCount = count($Result['path'])-1;
		?>			
    		<div id="path" class="float-break">
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
		<div id="bodcont" class="float-break">
			
		<div id="leftmenucont">
			  <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/leftmenu_user.tpl.php'));?>		      	      
        </div>		
    
         
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
		
    		<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?>
    			<div id="rightpadding">									
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
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>

</div>
</body>

</html>