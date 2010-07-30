<?php

try {
$Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    exit;
}

$randomTmpHash = erLhcoreClassModelForgotPassword::randomPassword(40);

erLhcoreClassImageConverter::getInstance()->converter->transform( 'anaglyph_left', $Image->file_path_filesystem, 'var/tmpfiles/'.$randomTmpHash.'_left.jpg' ); 
erLhcoreClassImageConverter::getInstance()->converter->transform( 'anaglyph_right', $Image->file_path_filesystem, 'var/tmpfiles/'.$randomTmpHash.'_right.jpg' ); 


$converter = new ezcImageConverter(
    new ezcImageConverterSettings(
        array( 
            new ezcImageHandlerSettings( 'imagemagick', 'erLhcoreClassGalleryImagemagickHandler' ),
            new ezcImageHandlerSettings( 'gd','erLhcoreClassGalleryGDHandler' )
        )
    )
);
            
$converter->createTransformation(
    'anaglyph',
    array( 
        new ezcImageFilter( 
            'anaglyphImage',
            array( 
                 'imageRight' => 'var/tmpfiles/'.$randomTmpHash.'_right.jpg' // Right side                    
            )
        ),
    ),
    array( 
        'image/jpeg',
        'image/png'
    ),
    new ezcImageSaveOptions(array('quality' => 100))
);

$converter->transform( 'anaglyph', 'var/tmpfiles/'.$randomTmpHash.'_left.jpg', 'var/tmpfiles/'.$randomTmpHash.'_anaglyph.jpg' );
        
$imageAnalyzer = new ezcImageAnalyzer( 'var/tmpfiles/'.$randomTmpHash.'_anaglyph.jpg'  );

header('Content-type: '.$imageAnalyzer->mime );
echo file_get_contents('var/tmpfiles/'.$randomTmpHash.'_anaglyph.jpg');	

// Cleanup
unlink('var/tmpfiles/'.$randomTmpHash.'_left.jpg');
unlink('var/tmpfiles/'.$randomTmpHash.'_right.jpg');
unlink('var/tmpfiles/'.$randomTmpHash.'_anaglyph.jpg');

exit;
