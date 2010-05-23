<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_duplicate_collection";
$def->class = "erLhcoreClassModelGalleryDuplicateCollection";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['time'] = new ezcPersistentObjectProperty();
$def->properties['time']->columnName   = 'time';
$def->properties['time']->propertyName = 'time';
$def->properties['time']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->relations["erLhcoreClassModelGalleryDuplicateImage"] = new ezcPersistentOneToManyRelation(
	"lh_gallery_duplicate_collection",
	"lh_gallery_duplicate_image"
);

$def->relations["erLhcoreClassModelGalleryDuplicateImage"]->columnMap = array(
	new ezcPersistentSingleTableMap(
	"id",
	"duplicate_collection_id"
	)
);

return $def; 

?>