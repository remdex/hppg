<?php

class erLhcoreClassGalleryBatch {

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

	public static function hasSubdir($dir)
	{
		$d = dir($dir);
		$directory  = array();
		$hasDir = false;
		while (false !== ($entry = $d->read())) {
			if (is_dir($dir.'/'.$entry.'/') && $entry != '.' && $entry != '..' ) $hasDir = true;
		}

		$d->close();

		return $hasDir;
	}

	/**
	 * Normalize path to web safe structure
	 * */
	public static function normalizePath($dir)
	{
	    $partsPath = explode('/',$dir);
        $pathCurrent = '';
        
        foreach ($partsPath as $key => $path)
        {
            $pathOriginal = $pathCurrent;
            $pathCurrent .= $path . '/';            
            $normalisedPath = preg_replace('#[^a-zA-Z0-9_-]+#','',$path);            
            // If paths differs and directory was not already normalized
            if ( $normalisedPath != $path && !is_dir($pathOriginal . $normalisedPath) && is_dir(rtrim($pathCurrent,'/')) ) {                
                rename(rtrim($pathCurrent,'/') , $pathOriginal . $normalisedPath);
            }
            $pathCurrent = $pathOriginal . $normalisedPath . '/';           
        }
        
        return rtrim($pathCurrent,'/');	    
	}
	
	/**
	 * Normalize filename to avoid any erros on urlencode, etc.
	 * */
	public static function normalizeFilename($filename,$path)
	{	    
	    $normalisedFilename = preg_replace("#[^a-zA-Z0-9_\.-]+#",'',$filename); 	    	    
	    if ($normalisedFilename != $filename && file_exists($path . '/' . $filename)) {
	        rename($path . '/' . $filename,$path . '/' . $normalisedFilename);
	        $filename = $normalisedFilename;
	    }
	    
	    return $filename;
	}
	
	public static function listDirectoryRecursive($dir = 'albums')
	{
		$data = ezcBaseFile::findRecursive(
		$dir,
		array( '@(jpg|png|jpeg|JPG|PNG|GIF|gif|bmp|mpg|mpeg|wmv|avi|swf|ogv|SWF|OGV|BMP|JPEG)$@' )
		);
		return $data;
	}
	
	
}

?>