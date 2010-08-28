<div class="picture-comments" id="comment-container">
    <div class="sub-header">
    <h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Picture comments')?></h3>
    </div>
    <?php $comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_'.$image->pid,'filter' => array('pid' => $image->pid))); 
    
    if (count($comments) > 0) :
    ?>
    <ul>
    <?php foreach ($comments as $comment): ?>
    <li>
    <span class="author"><?=htmlspecialchars($comment->msg_author);?></span>
    <div class="right ct"><?=$comment->msg_date;?></div>
    <p class="msg-body"><?=htmlspecialchars($comment->msg_body)?></p>
    </li>
    <?php endforeach;?>
    </ul>
    <?else:?>
    <p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','No comments')?></p>
    <?endif;?>
    
    <div class="comment-form">
        <? if (isset($commentErrArr)) : ?>
        <ul class="error-list">
            <?foreach ($commentErrArr as $error) :?>
                <li><?=$error?></li>
            <?php endforeach;?>
        </ul>
        <?endif;?>
        
        <? if (isset($commentStored)) : ?>
        <ul class="ok">
            <li><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Comment stored')?></li>
        </ul>
        <?endif;?>
        
        <form action="#comment-container" method="post" >
            <h4><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Leave a reply')?></h4>
            <div class="in-blk">
            <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Nick')?>:</label>
            <input type="text" name="Name" value="<?=htmlspecialchars($comment_new->msg_author)?>" maxlength="25" class="inputfield"/><i> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Max 25 characters')?></i>
            </div>
        
            <div class="in-blk">
            <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Message')?>:</label>
            <textarea name="CommentBody" rows="5" cols="10" class="default-textarea" ><?=htmlspecialchars($comment_new->msg_body)?></textarea>
            </div>
        
            <div class="in-blk">
            <label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Safe code')?></label>
            <input type="text" class="inputfield" name="CaptchaCode" value="" /><br />
            <img src="<?=erLhcoreClassDesign::baseurl('/captcha/image/comment/')?><?php echo time();?>" alt="" />
            </div>    
            <input type="submit" class="default-button" name="StoreComment" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Send')?>"/>
        </form>
    </div>
</div>