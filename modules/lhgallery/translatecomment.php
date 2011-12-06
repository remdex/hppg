<?php

try {
    $comment = erLhcoreClassModelGalleryComment::fetch((int)$Params['user_parameters']['msg_id']);
    $apiKey = erLhcoreClassModelSystemConfig::fetch('google_translate_api_key')->current_value;
} catch (Exception $e){
    exit;
}

if ($apiKey != '') {    
    $sourceTarget = '';
    if ($comment->lang != '') {
        $sourceTarget = '&source='.$comment->lang;
    }
    
    $destinationSiteaccess = erConfigClassLhConfig::getInstance()->getSetting( 'site_access_options', $Params['user_parameters']['siteaccess'] );
    $request = "https://www.googleapis.com/language/translate/v2?key={$apiKey}&target={$destinationSiteaccess['content_language']}$sourceTarget&q=".urlencode($comment->msg_body);
        
    $response = file_get_contents($request);
    $data = json_decode($response,true);
    
    if (isset($data['data']['translations'][0]['translatedText']))
    {
        $translatedText = erLhcoreClassBBCode::make_clickable(htmlspecialchars($data['data']['translations'][0]['translatedText']));    
        if ($comment->lang == '') {
            $comment->lang = $data['data']['translations'][0]['detectedSourceLanguage'];
            $comment->saveThis();
        }
    } else {
       $translatedText = erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body));
    }
} else {
    $translatedText = erLhcoreClassBBCode::make_clickable(htmlspecialchars($comment->msg_body));
}

echo json_encode(array('result' => $translatedText));
exit;