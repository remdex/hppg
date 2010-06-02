<?php


class erLhcoreClassImageConverter{
      
   public $converter;
   private static $instance = null;
   
   function __construct()
   {
        $this->converter = new ezcImageConverter(
                new ezcImageConverterSettings(
                    array( 
                        new ezcImageHandlerSettings( 'imagemagick', 'ezcImageImagemagickHandler' ),
                        new ezcImageHandlerSettings( 'gd', 'ezcImageGdHandler' )
                    )
                )
            );
                       
            $this->converter->createTransformation(
                'thumbbig',
                array( 
                    new ezcImageFilter( 
                        'scale',
                        array( 
                            'width'     => (int)erLhcoreClassModelSystemConfig::fetch('normal_thumbnail_width_x')->current_value,                        
                            'height'     => (int)erLhcoreClassModelSystemConfig::fetch('normal_thumbnail_width_y')->current_value,                        
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                )
            );
            
            $this->converter->createTransformation( 'jpeg', array(), array( 'image/jpeg' ) ); 
             
          
            /**
             * Two options
             * croppedThumbnail 
             * OR
             * scale
             * */ 
            $this->converter->createTransformation(
                'thumb',
                array( 
                    new ezcImageFilter( 
                        erLhcoreClassModelSystemConfig::fetch('thumbnail_scale_algorithm')->current_value,
                        array( 
                            'width'     => (int)erLhcoreClassModelSystemConfig::fetch('thumbnail_width_x')->current_value, 
                            'height'    => (int)erLhcoreClassModelSystemConfig::fetch('thumbnail_width_y')->current_value,                            
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                )
            ); 
          
        }
   
   
    public static function getInstance()  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassImageConverter();            
        }
        return self::$instance;
    }
    
    public static function isPhoto($file)
    { 
       if ($_FILES[$file]['error'] == 0)
       {       
           try {
               $image = new ezcImageAnalyzer( $_FILES[$file]['tmp_name'] );            
               if ($image->data->size < ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) && $image->data->width > 10 && $image->data->height > 10)
               {                   
                   return true;                   
                   
               } else 
               
               return false;
           } catch (Exception $e) {
               return false;
           }
       
       } else {
           return false;
       } 
    }
    
    public static function isPhotoLocal($filePAth)
    {              
           try {
               $image = new ezcImageAnalyzer( $filePAth );            
               if ($image->data->size < ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) && $image->data->width > 10 && $image->data->height > 10)
               {                   
                   return true;                   
                   
               } else                
               return false;
           } catch (Exception $e) {
               return false;
           }  
    }
    
    // Borowed from coppermine gallery
    public static function sanitizeFileName($str)
    {  
       static $forbidden_chars;
      if (!is_array($forbidden_chars)) {
        $mb_utf8_regex = '[\xE1-\xEF][\x80-\xBF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xC2-\xDF][\x80-\xBF]';
        if (function_exists('html_entity_decode')) {
          $chars = html_entity_decode('$/\\:*?&quot;&#39;&lt;&gt;|` &amp;', ENT_QUOTES, 'UTF-8');
        } else {
          $chars = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;', '&#39;'), array('&', '"', '<', '>', ' ', "'"), $CONFIG['forbiden_fname_char']);
        }
        preg_match_all("#$mb_utf8_regex".'|[\x00-\x7F]#', $chars, $forbidden_chars);
      }
      /**
       * $str may also come from $_POST, in this case, all &, ", etc will get replaced with entities.
       * Replace them back to normal chars so that the str_replace below can work.
       */
      $str = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $str);;
      $return = str_replace($forbidden_chars[0], '-', $str);
      $return = str_replace(array(')','('), array('',''), $return);
      $return = str_replace(' ', '-', $return);
    
      /**
      * Fix the obscure, misdocumented "feature" in Apache that causes the server
      * to process the last "valid" extension in the filename (rar exploit): replace all
      * dots in the filename except the last one with an underscore.
      */
      // This could be concatenated into a more efficient string later, keeping it in three
      // lines for better readability for now.
      $extension = strtolower(ltrim(substr($return,strrpos($return,'.')),'.'));
      $filenameWithoutExtension = str_replace('.' . $extension, '', $return);
      $return = str_replace('.', '-', $filenameWithoutExtension) .'.' . $extension;
      return $return;
    }
    
    public static function getExtension($fileName){
        return current(end(explode('.',$fileName)));
    }
 
}

?>