<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_duplicate_image";
$def->class = "erLhcoreClassModelGalleryDuplicateImage";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );
  
$def->properties['pid'] = new ezcPersistentObjectProperty();
$def->properties['pid']->columnName   = 'pid';
$def->properties['pid']->propertyName = 'pid';
$def->properties['pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;   
 
$def->properties['duplicate_collection_id'] = new ezcPersistentObjectProperty();
$def->properties['duplicate_collection_id']->columnName   = 'duplicate_collection_id';
$def->properties['duplicate_collection_id']->propertyName = 'duplicate_collection_id';
$def->properties['duplicate_collection_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

return $def; 

?>