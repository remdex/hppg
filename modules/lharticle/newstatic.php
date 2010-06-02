<?php
$tpl = erLhcoreClassTemplate::getInstance('lharticle/newstatic.tpl.php');

$Static = new erLhcoreClassModelArticleStatic();

if (isset($_POST['UpdateArticle']))
{
    $Static->content = $_POST['ArticleBody'];
    $Static->name = $_POST['ArticleName'];
    $Static->siteaccess = $_POST['Siteaccess'];
    
    erLhcoreClassArticle::getSession()->save($Static);
    
	$cache = CSCacheAPC::getMem();
	$cacheVersion = $cache->increaseCacheVersion('article_cache_version');
	
    erLhcoreClassModule::redirect('article/staticlist');
    return; 
}

$tpl->set('static',$Static);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/staticlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')),
array('title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('article/newstatic','New article'))
)

?>