
<div class="picture-comments" id="comment-container">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture comments')?></h3>
    </div>
    
    <div id="comments-list">
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
        
        <?else:?>
        <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','No comments')?></p>
        <?endif;?>
    </div>
    
       
    <div class="comment-form">
        
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
        <?endif;?>
                
        <h4><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Leave a reply')?></h4>
        <div class="in-blk">
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Nick')?>:</label>
        <input type="text" id="IDName" value="<?=htmlspecialchars($comment_new->msg_author)?>" maxlength="25" class="inputfield"/><i> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Max 25 characters')?></i>
        </div>
            
        <div class="in-blk">      
        <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Message')?>:</label>
        <textarea id="IDCommentBody" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Supported BB Code style tags: [b] [i] [u] [quote] :) :D :( :o :p ;)')?>" rows="5" cols="10" class="default-textarea" ><?=htmlspecialchars($comment_new->msg_body)?></textarea>
        </div>
                       
        <input type="button" class="default-button" id="CommentButtomStore" onclick="hw.addCheck(<?=time()?>,<?=$image->pid?>)" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Send')?>"/>
        
    </div>
</div>