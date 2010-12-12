<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_upload_archive";
$def->class = "erLhcoreClassModelGalleryUploadArchive";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['album_id'] = new ezcPersistentObjectProperty();
$def->properties['album_id']->columnName   = 'album_id';
$def->properties['album_id']->propertyName = 'album_id';
$def->properties['album_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['status'] = new ezcPersistentObjectProperty();
$def->properties['status']->columnName   = 'status';
$def->properties['status']->propertyName = 'status';
$def->properties['status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['filename'] = new ezcPersistentObjectProperty();
$def->properties['filename']->columnName   = 'filename';
$def->properties['filename']->propertyName = 'filename';
$def->properties['filename']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['album_name'] = new ezcPersistentObjectProperty();
$def->properties['album_name']->columnName   = 'album_name';
$def->properties['album_name']->propertyName = 'album_name';
$def->properties['album_name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['description'] = new ezcPersistentObjectProperty();
$def->properties['description']->columnName   = 'description';
$def->properties['description']->propertyName = 'description';
$def->properties['description']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['keywords'] = new ezcPersistentObjectProperty();
$def->properties['keywords']->columnName   = 'keywords';
$def->properties['keywords']->propertyName = 'keywords';
$def->properties['keywords']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>