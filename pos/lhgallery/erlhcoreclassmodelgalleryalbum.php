<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_albums";
$def->class = "erLhcoreClassModelGalleryAlbum";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'aid';
$def->idProperty->propertyName = 'aid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['title'] = new ezcPersistentObjectProperty();
$def->properties['title']->columnName   = 'title';
$def->properties['title']->propertyName = 'title';
$def->properties['title']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['description'] = new ezcPersistentObjectProperty();
$def->properties['description']->columnName   = 'description';
$def->properties['description']->propertyName = 'description';
$def->properties['description']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['pos'] = new ezcPersistentObjectProperty();
$def->properties['pos']->columnName   = 'pos';
$def->properties['pos']->propertyName = 'pos';
$def->properties['pos']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

$def->properties['category'] = new ezcPersistentObjectProperty();
$def->properties['category']->columnName   = 'category';
$def->properties['category']->propertyName = 'category';
$def->properties['category']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['keyword'] = new ezcPersistentObjectProperty();
$def->properties['keyword']->columnName   = 'keyword';
$def->properties['keyword']->propertyName = 'keyword';
$def->properties['keyword']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['owner_id'] = new ezcPersistentObjectProperty();
$def->properties['owner_id']->columnName   = 'owner_id';
$def->properties['owner_id']->propertyName = 'owner_id';
$def->properties['owner_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['public'] = new ezcPersistentObjectProperty();
$def->properties['public']->columnName   = 'public';
$def->properties['public']->propertyName = 'public';
$def->properties['public']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
 
$def->properties['addtime'] = new ezcPersistentObjectProperty();
$def->properties['addtime']->columnName   = 'addtime';
$def->properties['addtime']->propertyName = 'addtime';
$def->properties['addtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['album_pid'] = new ezcPersistentObjectProperty();
$def->properties['album_pid']->columnName   = 'album_pid';
$def->properties['album_pid']->propertyName = 'album_pid';
$def->properties['album_pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
  
$def->properties['hidden'] = new ezcPersistentObjectProperty();
$def->properties['hidden']->columnName   = 'hidden';
$def->properties['hidden']->propertyName = 'hidden';
$def->properties['hidden']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 


$def->relations["erLhcoreClassModelGalleryImage"] = new ezcPersistentOneToManyRelation(
	"lh_gallery_images",
	"lh_gallery_albums"
);

$def->relations["erLhcoreClassModelGalleryImage"]->columnMap = array(
	new ezcPersistentSingleTableMap(
	"aid",
	"aid"
	)
);

return $def; 

?>