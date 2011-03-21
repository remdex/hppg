<script>
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script src="<?=erConfigClassLhConfig::getInstance()->conf->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::design('js/hw.js');?>?v=27"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>