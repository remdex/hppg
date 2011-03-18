<div class="exif-data hide-full">
<? 
$imageData = new ezcImageAnalyzer( $image->file_path_filesystem); 
$exifData = $imageData->data->exif;
?>
<ul>
<?php if (isset($exifData['COMPUTED']['UserComment'])) : ?>
    <li><strong>User comment:</strong> <?=htmlspecialchars($exifData['COMPUTED']['UserComment']);?></li>
<?php endif;?>
<?php if (isset($exifData['IFD0']['Make'])) : ?>
    <li><strong>Make:</strong> <?=htmlspecialchars($exifData['IFD0']['Make']);?></li>
<?php endif;?>
<?php if (isset($exifData['IFD0']['Model'])) : ?>
    <li><strong>Model:</strong> <?=htmlspecialchars($exifData['IFD0']['Model']);?></li>
<?php endif;?>
<?php if (isset($exifData['EXIF']['ExposureTime'])) : 
$parts = explode('/',$exifData['EXIF']['ExposureTime']);?>
    <li><strong>Exposure Time:</strong> <?=$parts[0]/10;?>/<?=$parts[1]/10;?> s</li>
<?php endif;?>
<?php if (isset($exifData['EXIF']['FNumber'])) : 
$parts = explode('/',$exifData['EXIF']['FNumber']);?>
    <li><strong>FNumber:</strong> <?=$parts[0]/10;?></li>
<?php endif;?>
<?php if (isset($exifData['EXIF']['ISOSpeedRatings'])) : ?> 
    <li><strong>ISOSpeedRatings:</strong> <?=htmlspecialchars($exifData['EXIF']['ISOSpeedRatings']);?></li>
<?php endif;?>
<?php if (isset($exifData['EXIF']['FocalLength'])) :
$parts = explode('/',$exifData['EXIF']['FocalLength']);?> 
    <li><strong>FocalLength:</strong> <?=number_format($parts[0]/10,2,'.',',');?> mm</li>
<?php endif;?>
</ul>
</div>