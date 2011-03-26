
<div class="picture-comments" id="comment-container">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture comments')?></h3>
    </div>
    
    <div id="comments-list">
        <?php $comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_'.$image->pid,'filter' => array('pid' => $image->pid)));     
        if (count($comments) > 0) :
        ?>
        <ul>
        <?php foreach ($comments as $comment): ?>
        <li>
        
        <div class="float-break head-sub-com">
            <div class="right lang-box">
            <a href="#" class="tr-lnk" rel="<?=$comment->msg_id?>" title="Click to translate comment to other language">| Translate to</a>
            </div>
              
            <span class="author"><?=htmlspecialchars($comment->msg_author);?></span>
            <div class="right ct"><?=$comment->msg_date;?></div>
        </div>
        
        
        <p class="msg-body" id="msg_bd_<?=$comment->msg_id?>"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body))?>    
        <?php endforeach;?>

        </ul>
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