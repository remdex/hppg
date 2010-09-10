<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_duplicate_image_hash";
$def->class = "erLhcoreClassModelGalleryDuplicateImageHash";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
  
$def->properties['hash'] = new ezcPersistentObjectProperty();
$def->properties['hash']->columnName   = 'hash';
$def->properties['hash']->propertyName = 'hash';
$def->properties['hash']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;   
 

return $def; 

?>