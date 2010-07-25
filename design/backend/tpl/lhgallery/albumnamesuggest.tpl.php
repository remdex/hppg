<ul>
<?php foreach ($albums as $album) : ?>
<li><input type="radio" name="AlbumDestinationDirectory<?=$key_directory?>" value="<?=$album->aid?>" /> <?=htmlspecialchars($album->title)?></li>
<?php endforeach;?>
</ul>