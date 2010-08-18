<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_categorys";
$def->class = "erLhcoreClassModelGalleryCategory";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'cid';
$def->idProperty->propertyName = 'cid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );

$def->properties['owner_id'] = new ezcPersistentObjectProperty();
$def->properties['owner_id']->columnName   = 'owner_id';
$def->properties['owner_id']->propertyName = 'owner_id';
$def->properties['owner_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['name'] = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['description'] = new ezcPersistentObjectProperty();
$def->properties['description']->columnName   = 'description';
$def->properties['description']->propertyName = 'description';
$def->properties['description']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
 
$def->properties['pos'] = new ezcPersistentObjectProperty();
$def->properties['pos']->columnName   = 'pos';
$def->properties['pos']->propertyName = 'pos';
$def->properties['pos']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

$def->properties['parent'] = new ezcPersistentObjectProperty();
$def->properties['parent']->columnName   = 'parent';
$def->properties['parent']->propertyName = 'parent';
$def->properties['parent']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['hide_frontpage'] = new ezcPersistentObjectProperty();
$def->properties['hide_frontpage']->columnName   = 'hide_frontpage';
$def->properties['hide_frontpage']->propertyName = 'hide_frontpage';
$def->properties['hide_frontpage']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['has_albums'] = new ezcPersistentObjectProperty();
$def->properties['has_albums']->columnName   = 'has_albums';
$def->properties['has_albums']->propertyName = 'has_albums';
$def->properties['has_albums']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->relations["erLhcoreClassModelGalleryAlbum"] = new ezcPersistentOneToManyRelation(
	"lh_gallery_albums",
	"lh_gallery_categorys"
);

$def->relations["erLhcoreClassModelGalleryAlbum"]->columnMap = array(
	new ezcPersistentSingleTableMap(
	"cid",
	"category"
	)
);

$def->relations["erLhcoreClassModelGalleryCategory"] = new ezcPersistentOneToManyRelation(
	"lh_gallery_categorys",
	"lh_gallery_categorys"
);

$def->relations["erLhcoreClassModelGalleryCategory"]->columnMap = array(
	new ezcPersistentSingleTableMap(
	"cid",
	"parent"
	)
);

return $def; 

?>