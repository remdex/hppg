<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head.tpl.php'));?>
</head>
<body>

<div id="container">

<div id="main-header-bg" class="float-break">
    <div id="logo">
        <a href="<?=erLhcoreClassDesign::baseurl('/')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Home')?>"><img src="<?=erLhcoreClassDesign::design('images/newdesign/logo.jpg')?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" alt="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" title="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?>" /></a>
    </div>
    <?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/search_box.tpl.php'));?>
</div>

<div class="top-menu float-break">

<?if (isset($Result['rss'])) : ?>
<a class="rss-page" href="<?=$Result['rss']['url'];?>" title="<?=$Result['rss']['title']?>" /></a>
<? else :?>
<a class="rss-page" href="<?=erLhcoreClassDesign::baseurl('/gallery/lastuploadsrss/')?>" title="Last uploaded images" /></a>
<?endif;?>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_top_menu.tpl.php'));?>
</div>

<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>

	<div id="bodcont" class="float-break">
		        	
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