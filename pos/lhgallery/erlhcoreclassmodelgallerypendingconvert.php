<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_pending_convert";
$def->class = "erLhcoreClassModelGalleryPendingConvert";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
  
$def->properties['status'] = new ezcPersistentObjectProperty();
$def->properties['status']->columnName   = 'status';
$def->properties['status']->propertyName = 'status';
$def->properties['status']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

return $def; 

?>