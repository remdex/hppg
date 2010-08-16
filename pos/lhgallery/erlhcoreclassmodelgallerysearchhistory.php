<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_searchhistory";
$def->class = "erLhcoreClassModelGallerySearchHistory";

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

$def->properties['last_search'] = new ezcPersistentObjectProperty();
$def->properties['last_search']->columnName   = 'last_search';
$def->properties['last_search']->propertyName = 'last_search';
$def->properties['last_search']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['crc32'] = new ezcPersistentObjectProperty();
$def->properties['crc32']->columnName   = 'crc32';
$def->properties['crc32']->propertyName = 'crc32';
$def->properties['crc32']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['searches_done'] = new ezcPersistentObjectProperty();
$def->properties['searches_done']->columnName   = 'searches_done';
$def->properties['searches_done']->propertyName = 'searches_done';
$def->properties['searches_done']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def; 

?>