<?php

class erLhcoreClassGalleryBatch{
    
    
    
   function __construct()
   {
 
   }

   public static function listDirectory($dir = 'albums',$files = false)
   {
       $d = dir($dir);
       $directory  = array();
         
       while (false !== ($entry = $d->read())) {
           
           if ($files == false)
           {
              if (!is_dir($dir.'/'.$entry.'/') || $entry == '.' || $entry == '..' ) continue;                    
              $directory[] = $dir.'/'.$entry;
           } else {           
             if (is_dir($dir.'/'.$entry.'/') || $entry == '.' || $entry == '..' ) continue;                    
             $directory[] = $dir.'/'.$entry;
           }
       }
       
       $d->close();
       
       return $directory;
   }
   
   public static function listDirectoryRecursive($dir = 'albums')
   {
  
         $data = ezcBaseFile::findRecursive(
         $dir,
         array( '@(jpg|png|jpeg|JPEG)$@' )
         );
//          print_r($data);       
       return $data;
   }

}


?>