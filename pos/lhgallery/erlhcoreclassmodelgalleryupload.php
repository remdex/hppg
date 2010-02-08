<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_upload";
$def->class = "erLhcoreClassModelGalleryUpload";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['album_id'] = new ezcPersistentObjectProperty();
$def->properties['album_id']->columnName   = 'album_id';
$def->properties['album_id']->propertyName = 'album_id';
$def->properties['album_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['created'] = new ezcPersistentObjectProperty();
$def->properties['created']->columnName   = 'created';
$def->properties['created']->propertyName = 'created';
$def->properties['created']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['user_id'] = new ezcPersistentObjectProperty();
$def->properties['user_id']->columnName   = 'user_id';
$def->properties['user_id']->propertyName = 'user_id';
$def->properties['user_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['hash'] = new ezcPersistentObjectProperty();
$def->properties['hash']->columnName   = 'hash';
$def->properties['hash']->propertyName = 'hash';
$def->properties['hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>