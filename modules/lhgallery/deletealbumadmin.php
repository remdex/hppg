<?php

// Simple is it? :)
$category = $Params['user_object']->category;
$Params['user_object']->removeThis();


erLhcoreClassModule::redirect('gallery/admincategorys/'.$category);
exit;
