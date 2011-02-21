<?php

$comment = erLhcoreClassModelGalleryComment::fetch($Params['user_parameters']['msg_id']);
$apiKey = erLhcoreClassModelSystemConfig::fetch('google_translate_api_key')->current_value;

if ($apiKey != '') {    
    $sourceTarget = '';
    if ($comment->lang != '') {
        $sourceTarget = '&source='.$comment->lang;
    }
    
    $destinationSiteaccess = erConfigClassLhConfig::getInstance()->conf->getSetting( 'site_access_options', $Params['user_parameters']['siteaccess'] );
    $request = "https://www.googleapis.com/language/translate/v2?key={$apiKey}&target={$destinationSiteaccess['content_language']}$sourceTarget&q=".urlencode($comment->msg_body);
        
    $response = file_get_contents($request);
    $data = json_decode($response,true);
    
    if (isset($data['data']['translations'][0]['translatedText']))
    {
        $translatedText = erLhcoreClassGallery::make_clickable(nl2br(htmlspecialchars($data['data']['translations'][0]['translatedText'])));    
        if ($comment->lang == '') {
            $comment->lang = $data['data']['translations'][0]['detectedSourceLanguage'];
            $comment->saveThis();
        }
    } else {
       $translatedText = erLhcoreClassGallery::make_clickable(nl2br(htmlspecialchars($comment->msg_body)));
    }
} else {
    $translatedText = erLhcoreClassGallery::make_clickable(nl2br(htmlspecialchars($comment->msg_body)));
}

echo json_encode(array('result' => $translatedText));
exit;