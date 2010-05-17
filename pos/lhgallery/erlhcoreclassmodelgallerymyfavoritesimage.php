<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_myfavorites_images";
$def->class = "erLhcoreClassModelGalleryMyfavoritesImage";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['session_id'] = new ezcPersistentObjectProperty();
$def->properties['session_id']->columnName   = 'session_id';
$def->properties['session_id']->propertyName = 'session_id';
$def->properties['session_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
 
$def->properties['pid'] = new ezcPersistentObjectProperty();
$def->properties['pid']->columnName   = 'pid';
$def->properties['pid']->propertyName = 'pid';
$def->properties['pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
   
return $def; 

?>