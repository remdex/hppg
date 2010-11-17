<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_searchhistory";
$def->class = "erLhcoreClassModelGallerySearchHistory";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'keyword';
$def->idProperty->propertyName = 'keyword';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );

$def->properties['countresult'] = new ezcPersistentObjectProperty();
$def->properties['countresult']->columnName   = 'countresult';
$def->properties['countresult']->propertyName = 'countresult';
$def->properties['countresult']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['last_search'] = new ezcPersistentObjectProperty();
$def->properties['last_search']->columnName   = 'last_search';
$def->properties['last_search']->propertyName = 'last_search';
$def->properties['last_search']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['searches_done'] = new ezcPersistentObjectProperty();
$def->properties['searches_done']->columnName   = 'searches_done';
$def->properties['searches_done']->propertyName = 'searches_done';
$def->properties['searches_done']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>