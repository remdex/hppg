<?php

class erLhcoreClassGalleryImagemagickHandler extends ezcImageImagemagickHandler {
	
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
        $data = getimagesize( $this->getActiveResource() );

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
        
        $this->addFilterOption(
            $this->getActiveReference(),
            '-composite',
            '' 
        );

        $this->addFilterOption(
            $this->getActiveReference(),
            '-geometry',
            ( $width !== false ? $width : "" ) . ( $height !== false ? "x$height" : "" ) . "+$posX+$posY"
        );

        $this->addCompositeImage( $this->getActiveReference(), $image );

    }
	
}