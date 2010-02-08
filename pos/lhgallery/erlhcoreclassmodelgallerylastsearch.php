<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_lastsearch";
$def->class = "erLhcoreClassModelGalleryLastSearch";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['countresult'] = new ezcPersistentObjectProperty();
$def->properties['countresult']->columnName   = 'countresult';
$def->properties['countresult']->propertyName = 'countresult';
$def->properties['countresult']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['keyword'] = new ezcPersistentObjectProperty();
$def->properties['keyword']->columnName   = 'keyword';
$def->properties['keyword']->propertyName = 'keyword';
$def->properties['keyword']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 

return $def; 

?>