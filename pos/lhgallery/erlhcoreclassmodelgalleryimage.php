<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_images";
$def->class = "erLhcoreClassModelGalleryImage";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['aid'] = new ezcPersistentObjectProperty();
$def->properties['aid']->columnName   = 'aid';
$def->properties['aid']->propertyName = 'aid';
$def->properties['aid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['filepath'] = new ezcPersistentObjectProperty();
$def->properties['filepath']->columnName   = 'filepath';
$def->properties['filepath']->propertyName = 'filepath';
$def->properties['filepath']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['filename'] = new ezcPersistentObjectProperty();
$def->properties['filename']->columnName   = 'filename';
$def->properties['filename']->propertyName = 'filename';
$def->properties['filename']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['filesize'] = new ezcPersistentObjectProperty();
$def->properties['filesize']->columnName   = 'filesize';
$def->properties['filesize']->propertyName = 'filesize';
$def->properties['filesize']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['total_filesize'] = new ezcPersistentObjectProperty();
$def->properties['total_filesize']->columnName   = 'total_filesize';
$def->properties['total_filesize']->propertyName = 'total_filesize';
$def->properties['total_filesize']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['pwidth'] = new ezcPersistentObjectProperty();
$def->properties['pwidth']->columnName   = 'pwidth';
$def->properties['pwidth']->propertyName = 'pwidth';
$def->properties['pwidth']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['pheight'] = new ezcPersistentObjectProperty();
$def->properties['pheight']->columnName   = 'pheight';
$def->properties['pheight']->propertyName = 'pheight';
$def->properties['pheight']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['hits'] = new ezcPersistentObjectProperty();
$def->properties['hits']->columnName   = 'hits';
$def->properties['hits']->propertyName = 'hits';
$def->properties['hits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['ctime'] = new ezcPersistentObjectProperty();
$def->properties['ctime']->columnName   = 'ctime';
$def->properties['ctime']->propertyName = 'ctime';
$def->properties['ctime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['owner_id'] = new ezcPersistentObjectProperty();
$def->properties['owner_id']->columnName   = 'owner_id';
$def->properties['owner_id']->propertyName = 'owner_id';
$def->properties['owner_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['pic_rating'] = new ezcPersistentObjectProperty();
$def->properties['pic_rating']->columnName   = 'pic_rating';
$def->properties['pic_rating']->propertyName = 'pic_rating';
$def->properties['pic_rating']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['votes'] = new ezcPersistentObjectProperty();
$def->properties['votes']->columnName   = 'votes';
$def->properties['votes']->propertyName = 'votes';
$def->properties['votes']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['title'] = new ezcPersistentObjectProperty();
$def->properties['title']->columnName   = 'title';
$def->properties['title']->propertyName = 'title';
$def->properties['title']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['caption'] = new ezcPersistentObjectProperty();
$def->properties['caption']->columnName   = 'caption';
$def->properties['caption']->propertyName = 'caption';
$def->properties['caption']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['keywords'] = new ezcPersistentObjectProperty();
$def->properties['keywords']->columnName   = 'keywords';
$def->properties['keywords']->propertyName = 'keywords';
$def->properties['keywords']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['pic_raw_ip'] = new ezcPersistentObjectProperty();
$def->properties['pic_raw_ip']->columnName   = 'pic_raw_ip';
$def->properties['pic_raw_ip']->propertyName = 'pic_raw_ip';
$def->properties['pic_raw_ip']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['approved'] = new ezcPersistentObjectProperty();
$def->properties['approved']->columnName   = 'approved';
$def->properties['approved']->propertyName = 'approved';
$def->properties['approved']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['mtime'] = new ezcPersistentObjectProperty();
$def->properties['mtime']->columnName   = 'mtime';
$def->properties['mtime']->propertyName = 'mtime';
$def->properties['mtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['comtime'] = new ezcPersistentObjectProperty();
$def->properties['comtime']->columnName   = 'comtime';
$def->properties['comtime']->propertyName = 'comtime';
$def->properties['comtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['has_preview'] = new ezcPersistentObjectProperty();
$def->properties['has_preview']->columnName   = 'has_preview';
$def->properties['has_preview']->propertyName = 'has_preview';
$def->properties['has_preview']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['anaglyph'] = new ezcPersistentObjectProperty();
$def->properties['anaglyph']->columnName   = 'anaglyph';
$def->properties['anaglyph']->propertyName = 'anaglyph';
$def->properties['anaglyph']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['rtime'] = new ezcPersistentObjectProperty();
$def->properties['rtime']->columnName   = 'rtime';
$def->properties['rtime']->propertyName = 'rtime';
$def->properties['rtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['media_type'] = new ezcPersistentObjectProperty();
$def->properties['media_type']->columnName   = 'media_type';
$def->properties['media_type']->propertyName = 'media_type';
$def->properties['media_type']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->relations["erLhcoreClassModelGalleryComment"] = new ezcPersistentOneToManyRelation(
	"lh_gallery_images",
	"lh_gallery_comments"
);

$def->relations["erLhcoreClassModelGalleryComment"]->columnMap = array(
	new ezcPersistentSingleTableMap(
	"pid",
	"pid"
	)
);

return $def; 

?>