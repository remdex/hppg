<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_face_data";
$def->class = "erLhcoreClassModelGalleryFaceData";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );

$def->properties['data'] = new ezcPersistentObjectProperty();
$def->properties['data']->columnName   = 'data';
$def->properties['data']->propertyName = 'data';
$def->properties['data']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['sphinx_data'] = new ezcPersistentObjectProperty();
$def->properties['sphinx_data']->columnName   = 'sphinx_data';
$def->properties['sphinx_data']->propertyName = 'sphinx_data';
$def->properties['sphinx_data']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;   
 

return $def; 

?>