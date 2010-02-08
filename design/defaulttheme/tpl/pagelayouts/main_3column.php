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
<div id="logo"><h1><a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><img src="<?=erLhcoreClassDesign::design('images/general/hentai-wallpapers.jpg');?>" alt="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>"></a></h1></div>

<div class="title-gallery">
<h1>Hentai wallpapers</h1>
<p>All materials published are the property of their owners!</p></div>

<div class="search-box">
<form action="<?=erLhcoreClassDesign::baseurl('/gallery/search')?>"><input type="text" title="Enter keyword or phrase" id="searchtext" onfocus="Javascript: if ( $('input#searchtext').val() == 'Search...') { $('input#searchtext').val('');}" onblur="Javascript: if ($('input#searchtext').val() == '') { $('input#searchtext').val('Search...'); }" class="keywordField" name="SearchText" value="<?=isset($Result['keyword']) ? htmlspecialchars($Result['keyword']) : 'Search...'?>" /> <input type="submit" class="default-button" name="doSearch" value="Search entire gallery"/></form>
</div>

</div>


	
	<div class="clearer"></div>
</div>
<div class="top-menu float-break">
<ul>
                    <?php
                    $currentUser = erLhcoreClassUser::instance();                       
                    if ($currentUser->isLogged()) : 
                    $UserData = $currentUser->getUserData();
                    ?>                                       	
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/index/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Account');?> - (<?echo $UserData->username?>)</li> 
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/logout/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Logout');?></a></li>                  
                    <? 
                    unset($UserData);                    
                    else : ?>                                    	
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/login/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Login');?></a></li>                          
                    	<li><a href="<?=erLhcoreClassDesign::baseurl('/user/registration/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Register');?></a></li>                          
                    <?
                    endif;
                    unset($currentUser);
                    ?>
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/popular/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular images');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploads/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/toprated/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lasthits/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?> </a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/lastcommented/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?></a></li>                  
                    <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/publicupload/')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Upload image');?></a></li>
                    </ul>
</div>
	<? if (isset($Result['path'])) : 		
		$pathElementCount = count($Result['path'])-1;
		?>			
    		<div id="path" class="float-break">
    		  <a href="/">Home &raquo;</a>
    		  
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
		      <div id="navcontainer">
                	<ul id="navlist">
                			<li><a href="<?=erLhcoreClassDesign::baseurl('user/account')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','My account');?></a></li>
                			<li><a href="<?=erLhcoreClassDesign::baseurl('gallery/myalbums')?>">&raquo; <?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Manage albums');?></a></li>
                	</ul>
                </div>		      
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
		
			<div id="rightpadding">									
					<div class="right-infobox">
					<div class="last-search-infobox">
					<h3>Last searches</h3>
					<ul>
					<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
					   <li><a href="<?=erLhcoreClassDesign::baseurl('/gallery/search/')?>(keyword)/<?=urlencode($search->keyword);?>">&raquo; <?=htmlspecialchars($search->keyword);?> (<?=$search->countresult;?>)</a></li>
					<?endforeach;?>
					</ul>
					</div>					
                    </div>									
		    </div>	
		    
		    <div class="right-infobox">
                    <div class="last-search-infobox">
                        <h3>Last viewed images</h3>
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
                               $title = ($title = $item->name_user) == '' ? 'preview version' : $title;
                               $Result .= '<li><a href="'.$item->url_path.$appendImageMode.'"><img title="See full size" src="'.erLhcoreClassDesign::imagePath($item->filepath.'thumb_'.urlencode($item->filename)).'" alt="'.htmlspecialchars($item->name_user).'" /></a></li>';
                            }                            
                            $Result .= '</ul>';
                            
                            $cache->store(md5($cacheVersion.'_lasthits_infobox'),$Result);
                         
                        }
                        echo $Result;
                        ?>

            </div>
            
            <script language="javascript" type="text/javascript">
<!--
var _hey_lt_w = "", _hey_lt_h = "", _hey_lt_c = "";
//-->
</script>
<script language="javascript1.2" type="text/javascript">
<!--
_hey_lt_w = screen.width; _hey_lt_h = screen.height; _hey_lt_c = navigator.appName.indexOf("Microsoft") >= 0 ? screen.colorDepth : screen.pixelDepth;
//-->
</script>
<script language="javascript" type="text/javascript">
<!--
document.write("<a target='_blank' href='http://www.hey.lt/details.php?id=hentaiwalls'><img width=88 height=31 border=0 src='//www.hey.lt/count.php?id=hentaiwalls&width=" + _hey_lt_w + "&height=" + _hey_lt_h + "&color=" + _hey_lt_c + "&referer=" + escape(document.referrer) + "' alt='Hey.lt - Nemokamas lankytojų skaitliukas'><\/a>");
//-->
</script>
<noscript>
<a target="_blank" href="http://www.hey.lt/details.php?id=hentaiwalls"><img width=88 height=31 border=0 src="//www.hey.lt/count.php?id=hentaiwalls" alt="Hey.lt - Nemokamas lankytojų skaitliukas"></a>
</noscript>
            								
            </div>
		    
		</div>
		
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
<br />

</div>
</body>

</html>