<html lang="<?=erLhcoreClassSystem::instance()->ContentLanguage?>">
<meta charset="utf-8">
<title><? 
if ((isset($Result['tittle_prepend']) && $Result['tittle_prepend'] != '')){ echo $Result['tittle_prepend'].' &laquo; ';}

if (isset($Result['title_path'])) : 
$ReverseOrder = $Result['title_path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
 <?=$pathItem['title'].' &laquo; '?>
<? endforeach;?>
<? elseif (isset($Result['path'])) : ?>
<? 
$ReverseOrder = $Result['path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
<?=$pathItem['title'].' &laquo; '?> 
<? endforeach;?>
<? endif; ?>
<?=erConfigClassLhConfig::getInstance()->getOverrideValue( 'site', 'title' )?></title>
<?php include(erLhcoreClassDesign::designtpl('pagelayouts/parts/page_head_css.tpl.php'));?>  
<link rel="icon" type="image/png" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?=erLhcoreClassDesign::design('images/favicon.ico')?>" />
<?php if (isset( $Result['canonical'])) : ?><link rel="canonical" href="<?=$Result['canonical']?>" /><?endif;?>
<meta name="Keywords" content="" />
<meta name="Description" content="<?=isset($Result['description_prepend']) ? $Result['description_prepend'].' ' : '' ?><?=erConfigClassLhConfig::getInstance()->getOverrideValue( 'site', 'description' )?>" />
<meta http-equiv="Content-Language" content="<?=erLhcoreClassSystem::instance()->ContentLanguage?>"/>
<?if (isset($Result['rss'])) : ?>
<link rel="alternate" type="application/rss+xml" title="<?=$Result['rss']['title']?>" href="<?=$Result['rss']['url'];?>" />
<? else :?>
<link rel="alternate" type="application/rss+xml" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploaded images')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploadsrss')?>" />
<?endif;?>