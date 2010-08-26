<?php



class erLhcoreClassGalleryArchive {
	
	public static function isSupportedArchive($filename)
	{
	    if ($_FILES[$filename]['size'] <= (int)erLhcoreClassModelSystemConfig::fetch('max_archive_size')->current_value*1024)
	    {
	        $fileNameAray = explode('.',$_FILES[$filename]['name']);
	        end($fileNameAray);	        
	        $extension = current($fileNameAray);
	                
	        return $extension === 'zip';
	    }
	    
	    return false;    
		
	}
	
}