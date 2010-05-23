<?php

// Delete duplicate object
$Params['user_object']->removeThis();

erLhcoreClassModule::redirect('gallery/duplicates');
exit;
