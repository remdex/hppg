<script>
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::design('js/hw.js');?>?v=2"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>