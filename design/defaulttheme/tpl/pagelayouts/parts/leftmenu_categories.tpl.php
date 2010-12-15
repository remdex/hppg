<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','All time')?></h3>
		<ul>													
		    <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/popular')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/toprated')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a>                  
		</ul>									
</div>

<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Interactive')?></h3>
		<ul>           
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploads')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?></a>  
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?> </a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastcommented')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastrated')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last rated');?></a>                 
		</ul>									
</div>

<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last 24 h.')?></h3>
		<ul>  
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/popularrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/ratedrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a>                 
		</ul>									
</div>

<?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true || erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'seach_by_color_enabled' ) === true) : ?>
<div class="left-infobox">
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search')?></h3>
		<ul>
		    <?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'sphinx', 'enabled' ) === true) : ?> 
		    <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by keyword');?></a>  
		    <?php endif;?>
		    
		    <?php if (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'seach_by_color_enabled' ) === true) : ?>               
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by color');?></a> 
            <?php endif;?>
		</ul>
</div>
<?php endif;?>