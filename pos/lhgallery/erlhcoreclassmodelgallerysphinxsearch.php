<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_sphinx_search";
$def->class = "erLhcoreClassModelGallerySphinxSearch";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );

$def->properties['title'] = new ezcPersistentObjectProperty();
$def->properties['title']->columnName   = 'title';
$def->properties['title']->propertyName = 'title';
$def->properties['title']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING; 

$def->properties['caption'] = new ezcPersistentObjectProperty();
$def->properties['caption']->columnName   = 'caption';
$def->properties['caption']->propertyName = 'caption';
$def->properties['caption']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['filename'] = new ezcPersistentObjectProperty();
$def->properties['filename']->columnName   = 'filename';
$def->properties['filename']->propertyName = 'filename';
$def->properties['filename']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['file_path'] = new ezcPersistentObjectProperty();
$def->properties['file_path']->columnName   = 'file_path';
$def->properties['file_path']->propertyName = 'file_path';
$def->properties['file_path']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['mtime'] = new ezcPersistentObjectProperty();
$def->properties['mtime']->columnName   = 'mtime';
$def->properties['mtime']->propertyName = 'mtime';
$def->properties['mtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['pid'] = new ezcPersistentObjectProperty();
$def->properties['pid']->columnName   = 'pid';
$def->properties['pid']->propertyName = 'pid';
$def->properties['pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['comtime'] = new ezcPersistentObjectProperty();
$def->properties['comtime']->columnName   = 'comtime';
$def->properties['comtime']->propertyName = 'comtime';
$def->properties['comtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['hits'] = new ezcPersistentObjectProperty();
$def->properties['hits']->columnName   = 'hits';
$def->properties['hits']->propertyName = 'hits';
$def->properties['hits']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['rtime'] = new ezcPersistentObjectProperty();
$def->properties['rtime']->columnName   = 'rtime';
$def->properties['rtime']->propertyName = 'rtime';
$def->properties['rtime']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['pic_rating'] = new ezcPersistentObjectProperty();
$def->properties['pic_rating']->columnName   = 'pic_rating';
$def->properties['pic_rating']->propertyName = 'pic_rating';
$def->properties['pic_rating']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['votes'] = new ezcPersistentObjectProperty();
$def->properties['votes']->columnName   = 'votes';
$def->properties['votes']->propertyName = 'votes';
$def->properties['votes']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['pwidth'] = new ezcPersistentObjectProperty();
$def->properties['pwidth']->columnName   = 'pwidth';
$def->properties['pwidth']->propertyName = 'pwidth';
$def->properties['pwidth']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['pheight'] = new ezcPersistentObjectProperty();
$def->properties['pheight']->columnName   = 'pheight';
$def->properties['pheight']->propertyName = 'pheight';
$def->properties['pheight']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['colors'] = new ezcPersistentObjectProperty();
$def->properties['colors']->columnName   = 'colors';
$def->properties['colors']->propertyName = 'colors';
$def->properties['colors']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['text_index'] = new ezcPersistentObjectProperty();
$def->properties['text_index']->columnName   = 'text_index';
$def->properties['text_index']->propertyName = 'text_index';
$def->properties['text_index']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>