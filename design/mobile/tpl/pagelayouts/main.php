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
	
		<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/path.tpl.php'));?>
				
		<div id="middcont">
			<div id="mainartcont">
			<div style="padding:2px">
			<?
			 echo $Result['content'];		
			?>			
			</div>
			</div>
		</div>
				
	</div>
	
<?php include_once(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_footer.tpl.php'));?>
<br />

</div>
</body>

</html>