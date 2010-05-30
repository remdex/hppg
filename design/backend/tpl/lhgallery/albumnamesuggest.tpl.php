<ul>
<?php foreach ($albums as $album) : ?>
<li><input type="radio" name="AlbumDestinationDirectory<?=$key_directory?>" value="<?=$album->aid?>" /> <?=$album->title?></li>
<?php endforeach;?>
</ul>