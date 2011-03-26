<? if (isset($commentErrArr)) : ?>
<ul class="error-list">
    <?foreach ($commentErrArr as $error) :?>
        <li><?=$error?>
    <?php endforeach;?>
</ul>
<?endif;?>
<? if (isset($commentStored)) : ?>
<ul class="ok">
    <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Comment stored')?>
</ul>
<--[SPLITTER]-->

<?php 
$imageCommentVersion = CSCacheAPC::getMem()->getCacheVersion('comments_'.$image->pid);         
$commentCount = erLhcoreClassModelGalleryComment::getCount(array('cache_key' => 'comments_count_v_'.$image->pid.'_'.$imageCommentVersion,'filter' => array('pid' => $image->pid)));

if ($commentCount > 0) :
$comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_v_'.$image->pid.'_'.$imageCommentVersion,'filter' => array('pid' => $image->pid)));
?>

<?php include(erLhcoreClassDesign::designtpl('lhgallery/comment_list_items.tpl.php')); ?>

<?php 
$pages = new lhPaginator();
$pages->items_total = $commentCount;
$pages->serverURL = erLhcoreClassDesign::baseurl('gallery/commentsajax').'/'.$image->pid;
$pages->setItemsPerPage(10);
$pages->paginate();
?>
        
<div class="comments-paginator">
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator_ajax.tpl.php')); ?>
</div>
        
<script>
hw.initCommentTranslations();
</script>
<?endif;?>
<?endif;?>