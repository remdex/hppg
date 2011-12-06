<script>
WWW_DIR_JAVASCRIPT = '<?=erLhcoreClassDesign::baseurl()?>';
</script>
<script src="<?=erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'css' )?><?=erLhcoreClassDesign::designJS('js/hw.js;js/markitup/jquery.markitup.js;js/markitup/sets/bbcode/set.js');?>"></script>
<?=isset($Result['additional_js']) ? $Result['additional_js'] : ''?>