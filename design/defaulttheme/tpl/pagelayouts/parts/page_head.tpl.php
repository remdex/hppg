<title><? 

if ((isset($Result['tittle_prepend']) && $Result['tittle_prepend'] != '')){ echo $Result['tittle_prepend'].' &laquo;';}

if (isset($Result['title_path'])) : 
$ReverseOrder = $Result['title_path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
 <?=$pathItem['title']?> &laquo;
<? endforeach;?>
<? elseif (isset($Result['path'])) : ?>
<? 
$ReverseOrder = $Result['path'];
krsort($ReverseOrder);
foreach ($ReverseOrder as $pathItem) : ?>
 <?=$pathItem['title']?> &laquo;
<? endforeach;?>
<? endif; ?>

<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'title' )?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="<?=erLhcoreClassDesign::design('css/style_site.css');?>" /> 
<link rel="icon" type="image/png" href="/design/defaulttheme/images/favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="/design/defaulttheme/images/favicon.ico" />
<meta name="Keywords" content="Hentai wallpapers" />
<meta name="Description" content="Hentai wallpapers resource. Dedicated to hentai anime, soyorama, garv wallpapers, all free. Users uploaded hentai wallpapers." />
<script type="text/javascript">
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script type="text/javascript" language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="<?=erLhcoreClassDesign::design('js/hw.js');?>?v=3"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>