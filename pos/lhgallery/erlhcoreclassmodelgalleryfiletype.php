<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_filetypes";
$def->class = "erLhcoreClassModelGalleryFiletype";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'extension';
$def->idProperty->propertyName = 'extension';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
  
$def->properties['mime'] = new ezcPersistentObjectProperty();
$def->properties['mime']->columnName   = 'mime';
$def->properties['mime']->propertyName = 'mime';
$def->properties['mime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 
  
$def->properties['content'] = new ezcPersistentObjectProperty();
$def->properties['content']->columnName   = 'content';
$def->properties['content']->propertyName = 'content';
$def->properties['content']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['player'] = new ezcPersistentObjectProperty();
$def->properties['player']->columnName   = 'player';
$def->properties['player']->propertyName = 'player';
$def->properties['player']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;  

return $def; 

?>