<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_popular24";
$def->class = "erLhcoreClassModelGalleryPopular24";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
  
$def->properties['hits'] = new ezcPersistentObjectProperty();
$def->properties['hits']->columnName   = 'hits';
$def->properties['hits']->propertyName = 'hits';
$def->properties['hits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
   
$def->properties['added'] = new ezcPersistentObjectProperty();
$def->properties['added']->columnName   = 'added';
$def->properties['added']->propertyName = 'added';
$def->properties['added']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

return $def; 

?>