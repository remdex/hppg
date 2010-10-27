<?php

$def = new ezcPersistentObjectDefinition();
$def->table = "lh_gallery_rated24";
$def->class = "erLhcoreClassModelGalleryRated24";

$def->idProperty = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName = 'pid';
$def->idProperty->propertyName = 'pid';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition(  'ezcPersistentManualGenerator' );
  
$def->properties['pic_rating'] = new ezcPersistentObjectProperty();
$def->properties['pic_rating']->columnName   = 'pic_rating';
$def->properties['pic_rating']->propertyName = 'pic_rating';
$def->properties['pic_rating']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT; 
   
$def->properties['votes'] = new ezcPersistentObjectProperty();
$def->properties['votes']->columnName   = 'votes';
$def->properties['votes']->propertyName = 'votes';
$def->properties['votes']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;
    
$def->properties['added'] = new ezcPersistentObjectProperty();
$def->properties['added']->columnName   = 'added';
$def->properties['added']->propertyName = 'added';
$def->properties['added']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;  

return $def; 

?>