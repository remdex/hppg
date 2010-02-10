<div class="header-list">
<div class="right order-nav">
    <a class="da<?=$mode == 'newdesc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>">Last uploaded first</a>
    <a class="ar<?=$mode == 'newasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/newasc">Last uploaded last</a>    
    <a class="da<?=$mode == 'popular' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/popular">Most popular first</a>
    <a class="ar<?=$mode == 'popularasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/popularasc">Most popular last</a>
    <a class="da<?=$mode == 'lasthits' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/lasthits">Last hits first</a>
    <a class="ar<?=$mode == 'lasthitsasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/lasthitsasc">Last hits last</a>    
    <a class="da<?=$mode == 'toprated' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/toprated">Top rated first</a>
    <a class="ar<?=$mode == 'topratedasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/topratedasc">Top rated last</a>    
    <a class="da<?=$mode == 'lastcommented' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/lastcommented">Last commented first</a>
    <a class="ar<?=$mode == 'lastcommentedasc' ? ' selor' : ''?>" href="<?=erLhcoreClassDesign::baseurl('/gallery/search/(keyword)/')?><?echo urlencode($keyword)?>/(sort)/lastcommentedasc">Last commented last</a>
</div>
<h1>Search results - <?=htmlspecialchars($keyword)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>

  <?php 
  
  include_once(erLhcoreClassDesign::designtpl('lhgallery/image_list.tpl.php'));?> 
  
<? } else { ?>

<p>Nothing found...</p>

<? } ?>

