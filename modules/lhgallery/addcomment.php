<?php

$currentUser = erLhcoreClassUser::instance();

$tpl = erLhcoreClassTemplate::getInstance( 'lhgallery/addcomment.tpl.php');

try {
    $Image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$Params['user_parameters']['image_id'] );
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$CommentData = new erLhcoreClassModelGalleryComment();

$nameField = 'captcha_'.$_SESSION[$_SERVER['REMOTE_ADDR']]['comment'];
$definition = array(
    'Name' => new ezcInputFormDefinitionElement(
        ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
    ),
    
    'CommentBody' => new ezcInputFormDefinitionElement(
        ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
    ),   
        
    $nameField => new ezcInputFormDefinitionElement(
        ezcInputFormDefinitionElement::OPTIONAL, 'string'
    )
);

$form = new ezcInputForm( INPUT_POST, $definition );
$Errors = array();

// Range is as big as image data cache
if ( !$form->hasValidData( $nameField ) || $form->$nameField == '' || $form->$nameField < time()-144000)
{
    $Errors[] =   erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Wrong captcha code!');
} 

$validUsername = false;
if ( !$form->hasValidData( 'Name' ) || $form->Name == '' )
{
    $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Please enter nick!');
} else {$CommentData->msg_author = $form->Name;$validUsername = true;}

if ($validUsername == true && (($currentUser->isLogged() && $currentUser->getUserData()->username != $form->Name) || ($currentUser->isLogged() == false)) && erLhcoreClassUser::getUserCount(array('filter' => array('username' => $form->Name))) > 0){
    $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Sutch username is already taken!');
}

if ( !$form->hasValidData( 'CommentBody' ) || trim($form->CommentBody) == '' || mb_strlen(trim($form->CommentBody)) > 500 || erLhcoreClassModelGalleryComment::isSpam(trim($form->CommentBody)))
{
    $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Please enter comment!');
} else $CommentData->msg_body = trim($form->CommentBody);


if (count($Errors) == 0)
{  
    
    $CommentData->pid = $Image->pid;
    $CommentData->msg_date = date('Y-m-d H:i:s');
    $CommentData->msg_hdr_ip = $_SERVER['REMOTE_ADDR'];
    $CommentData->author_md5_id = md5($CommentData->msg_author);

    if ($currentUser->isLogged())
    {
        $CommentData->author_id = $currentUser->getUserID();
    }
     
    erLhcoreClassGallery::getSession()->save($CommentData);
                
    $Image->comtime = time();
    erLhcoreClassGallery::getSession()->update($Image);
    
    //Clear cache
    CSCacheAPC::getMem()->delete('comments_'.$Image->pid);
    CSCacheAPC::getMem()->increaseCacheVersion('last_commented');
    CSCacheAPC::getMem()->increaseCacheVersion('last_commented_'.$Image->aid);
    CSCacheAPC::getMem()->increaseCacheVersion('last_commented_image_version_'.$Image->pid);
    
    erLhcoreClassGallery::expireShardIndexByIdentifier(array('last_commented'));
    
    erLhcoreClassGallery::expireShardIndexByIdentifier(array('album_id_'.$Image->aid),array('comtime DESC, pid DESC','comtime ASC, pid ASC'));
            
    // Update two attributes
	erLhcoreClassModelGallerySphinxSearch::indexAttributes($Image,array('comtime' => 'comtime'));
    
    $tpl->set('image',$Image);
    $tpl->set('commentStored',true);
    $tpl->set('comment_id',$CommentData->msg_id);
    
    $parts = explode('<--[SPLITTER]-->',trim($tpl->fetch()));
    echo json_encode(array('error' => 'false','status' => $parts[0],'comments' => $parts[1],'id' => $CommentData->msg_id));
       
}  else {     
    $tpl->set('commentErrArr',$Errors);
    echo json_encode(array('error' => 'true','status' => $tpl->fetch()));
}
exit;