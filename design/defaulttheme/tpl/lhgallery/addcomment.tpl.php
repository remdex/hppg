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
<?php $comments = erLhcoreClassModelGalleryComment::getComments(array('cache_key' => 'comments_'.$image->pid,'filter' => array('pid' => $image->pid)));     
if (count($comments) > 0) :
?>
<ul>
<?php foreach ($comments as $comment): ?>
<li id="com_<?=$comment->msg_id?>"<?php if ($comment_id == $comment->msg_id) :?> style="display:none;"<?php endif;?>>
<span class="author"><?=htmlspecialchars($comment->msg_author);?></span>
<div class="right ct"><?=$comment->msg_date;?></div>
<p class="msg-body"><?=nl2br(htmlspecialchars($comment->msg_body))?>    
<?php endforeach;?>
</ul>
<?else:?>
<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','No comments')?></p>
<?endif;?>      
<?endif;?>