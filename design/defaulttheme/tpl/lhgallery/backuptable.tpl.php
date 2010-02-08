<table width="100%">     
   <? foreach ($elements as $key => $album) : ?> 
       <? if (($counterThumbnail != 0 && $counterThumbnail % $itemsPerPage == 0)): ?></tr><?endif;?>
       <? if (($counterThumbnail==0) || $counterThumbnail % $itemsPerPage == 0): ?><tr><?endif;$counterThumbnail++;?>  
       <td width="<?=round(100/$itemsPerPage)?>%">       
       <h2><a href="<?=erLhcoreClassDesign::baseurl('gallery/album/')?><?=$album->aid?>"><?=htmlspecialchars($album->title)?></a></h2>       
       <?=$album->images_count;?> files.       
       </td>        
       <? if ($counterThumbnail == $elementsCount): ?></tr><?endif;?>  
    <? endforeach;?>
</table>