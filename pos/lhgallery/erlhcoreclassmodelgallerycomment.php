<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_comments";
$def->class = "erLhcoreClassModelGalleryComment";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'msg_id';
$def->idProperty->propertyName = 'msg_id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentNativeGenerator' );
 
$def->properties['pid'] = new ezcPersistentObjectProperty();
$def->properties['pid']->columnName   = 'pid';
$def->properties['pid']->propertyName = 'pid';
$def->properties['pid']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

$def->properties['author_id'] = new ezcPersistentObjectProperty();
$def->properties['author_id']->columnName   = 'author_id';
$def->properties['author_id']->propertyName = 'author_id';
$def->properties['author_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 

$def->properties['msg_date'] = new ezcPersistentObjectProperty();
$def->properties['msg_date']->columnName   = 'msg_date';
$def->properties['msg_date']->propertyName = 'msg_date';
$def->properties['msg_date']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['author_md5_id'] = new ezcPersistentObjectProperty();
$def->properties['author_md5_id']->columnName   = 'author_md5_id';
$def->properties['author_md5_id']->propertyName = 'author_md5_id';
$def->properties['author_md5_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['msg_hdr_ip'] = new ezcPersistentObjectProperty();
$def->properties['msg_hdr_ip']->columnName   = 'msg_hdr_ip';
$def->properties['msg_hdr_ip']->propertyName = 'msg_hdr_ip';
$def->properties['msg_hdr_ip']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['msg_body'] = new ezcPersistentObjectProperty();
$def->properties['msg_body']->columnName   = 'msg_body';
$def->properties['msg_body']->propertyName = 'msg_body';
$def->properties['msg_body']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['msg_author'] = new ezcPersistentObjectProperty();
$def->properties['msg_author']->columnName   = 'msg_author';
$def->properties['msg_author']->propertyName = 'msg_author';
$def->properties['msg_author']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['lang'] = new ezcPersistentObjectProperty();
$def->properties['lang']->columnName   = 'lang';
$def->properties['lang']->propertyName = 'lang';
$def->properties['lang']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

return $def; 

?>