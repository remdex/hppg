<script type="text/javascript">
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::design('js/hw.js');?>?v=1"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>