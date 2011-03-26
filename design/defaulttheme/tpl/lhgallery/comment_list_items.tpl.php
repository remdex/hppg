<ul>
<?php foreach ($comments as $comment): ?>
<li>        
<div class="float-break head-sub-com">
    <div class="right lang-box">
    <a href="#" class="tr-lnk" rel="<?=$comment->msg_id?>" title="Click to translate comment to other language">| Translate to</a>
    </div>
    <div class="right ct"><?=$comment->msg_date;?></div>             
    <span class="author"><?=htmlspecialchars($comment->msg_author);?></span>    
</div>
<p class="msg-body" id="msg_bd_<?=$comment->msg_id?>"><?=erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body))?>    
<?php endforeach; ?>   
</ul>