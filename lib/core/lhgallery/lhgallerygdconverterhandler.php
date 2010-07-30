<?php

class erLhcoreClassGalleryGDHandler extends ezcImageGdHandler {
	
	public function watermarkCenterAbsolute( $image, $posX, $posY, $width = false, $height = false )
    {
        if ( !is_string( $image ) || !file_exists( $image ) || !is_readable( $image ) )
        {
            throw new ezcBaseValueException( 'image', $image, 'string, path to an image file' );
        }
        if ( !is_int( $posX ) )
        {
            throw new ezcBaseValueException( 'posX', $posX, 'int' );
        }
        if ( !is_int( $posY ) )
        {
            throw new ezcBaseValueException( 'posY', $posY, 'int' );
        }
        if ( !is_int( $width ) && !is_bool( $width ) )
        {
            throw new ezcBaseValueException( 'width', $width, 'int/bool' );
        }
        if ( !is_int( $height ) && !is_bool( $height ) )
        {
            throw new ezcBaseValueException( 'height', $height, 'int/bool' );
        }
        
        $dataWatermark = getimagesize($image);
                
        $data[0] = imagesx( $this->getActiveResource() );
        $data[1] = imagesy( $this->getActiveResource() );
        
        $paddingX = $posX;
        $paddingY = $posY;
        
        $posX = $data[0]/2 - ($dataWatermark[0]/2);
        $posY = $data[1]/2 - ($dataWatermark[1]/2);
                 
        if ($posX < 0){        	
        	if ($width == false) {
        		$width = round($data[0] - $paddingX*2);
        	}        	 
        	$posX = $data[0]/2 - $width/2;
        	
        	//We have to adjust vertical position now
        	$posY = $data[1]/2 - ($dataWatermark[1] * ($width/$dataWatermark[0]))/2;
        }
                
        if ($posY < 0){        	
        	if ($height == false) {
        		$height = round($data[1] - $paddingY*2);
        	}        	
        	$posY = $data[1]/2 - $height/2;
        	
        	//We have to adjust horisontal position now
        	$posX = $data[0]/2 - ($dataWatermark[0] * ($height/$dataWatermark[1]))/2;
        }     
         
        // If new size set, both sizes have to be set
        if ($height !== false && $width == false)
        {
        	$width = $dataWatermark[0];
        }  
        
        if ($height == false && $width !== false)
        {
        	$height = $dataWatermark[1];        	
        }
                
        $originalRef = $this->getActiveReference();

        $originalWidth  = imagesx( $this->getActiveResource() );
        $originalHeight = imagesy( $this->getActiveResource() );
            
        $watermarkRef = $this->load( $image );
        if ( $width !== false && $height !== false )
        {
            $this->scale( (int)$width, (int)$height, ezcImageGeometryFilters::SCALE_BOTH );
        }
        
        imagecopy(
            $this->getReferenceData( $originalRef, "resource" ),                // resource $dst_im
            $this->getReferenceData( $watermarkRef, "resource" ),               // resource $src_im
            $posX,                                                              // int $dst_x
            $posY,                                                              // int $dst_y
            0,                                                                  // int $src_x
            0,                                                                  // int $src_y
            imagesx( $this->getReferenceData( $watermarkRef, "resource" ) ),    // int $src_w
            imagesy( $this->getReferenceData( $watermarkRef, "resource" ) )     // int $src_h
        );

        $this->close( $watermarkRef );
        
        // Restore original image reference
        $this->setActiveReference( $originalRef );
    }
    
    
    /**
     * @param $side 
     * 0 - left side cut
     * 1 - right side cut
     * */
    public function anaglyphImage($imageRight)
    {           
        $originalRef = $this->getActiveReference();

        $originalWidth  = imagesx( $this->getActiveResource() );
        $originalHeight = imagesy( $this->getActiveResource() );
            
        $rightImageRef = $this->load( $imageRight );  
                
        // Taken from http://instantsolve.net/blog/2008/06/creating-anaglyphs/ 
        $src_left = $this->getReferenceData( $originalRef, "resource" );
        $src_right = $this->getReferenceData( $rightImageRef, "resource" );
               
        $bwimage= imagecreatetruecolor($originalWidth, $originalHeight);
        //Reads the origonal colors pixel by pixel        
        for ($y=0;$y<$originalHeight;$y++){
        
        	for ($x=0;$x<$originalWidth;$x++){        
        		$rgb_left = imagecolorat($src_left,$x,$y);        
        		$r = ($rgb_left >> 16) & 0xFF;        
        		$rgb_right = imagecolorat($src_right,$x,$y);        
        		$g = ($rgb_right >> 8) & 0xFF;        
        		$b = $rgb_right & 0xFF;        
        		
        		//This is where we create the color which is a mix of the red, green and blue channels        
        		$color = imagecolorallocate($bwimage,$r,$g,$b);        
        		imagesetpixel($bwimage,$x,$y,$color);        
        	}        
        }
         
        $this->close( $rightImageRef );
        
        // Restore original image reference
        $this->setActiveReference( $originalRef );         
        $oldResource = $this->getReferenceData( $originalRef, 'resource' );        
        imagedestroy( $oldResource );        
                
        $this->setReferenceData( $originalRef, $bwimage, 'resource' );           
    }
    
    
    /**
     * @param $side 
     * 0 - left side cut
     * 1 - right side cut
     * */
    public function anaglyphImageSide($side = 0)
    {                
        $data[0] = imagesx( $this->getActiveResource() );
        $data[1] = imagesy( $this->getActiveResource() );
        
        $imageHalfWidth = (int)($data[0]/2);
        $imageHeight = $data[1];
                   
        if ($side == 0) {
            $this->crop(0,0,$imageHalfWidth,$imageHeight);
        } else {
            $this->crop($imageHalfWidth,0,$imageHalfWidth,$imageHeight);
        }            
    }
	
}