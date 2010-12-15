<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_pallete";
$def->class = "erLhcoreClassModelGalleryPallete";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );
  
$def->properties['red'] = new ezcPersistentObjectProperty();
$def->properties['red']->columnName   = 'red';
$def->properties['red']->propertyName = 'red';
$def->properties['red']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
  
$def->properties['green'] = new ezcPersistentObjectProperty();
$def->properties['green']->columnName   = 'green';
$def->properties['green']->propertyName = 'green';
$def->properties['green']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['blue'] = new ezcPersistentObjectProperty();
$def->properties['blue']->columnName   = 'blue';
$def->properties['blue']->propertyName = 'blue';
$def->properties['blue']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

return $def; 

?>