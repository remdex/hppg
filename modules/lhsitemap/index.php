<?php

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
     <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ( erConfigClassLhConfig::getInstance()->conf->getSetting('sitemap_settings','siteaccess_sitemaps') as $access ) {
        $accessUrl = $access != erConfigClassLhConfig::getInstance()->conf->getSetting('site','default_site_access') ? erLhcoreClassDesign::baseurldirect($access) : '';
        echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. $accessUrl . erLhcoreClassDesign::baseurldirect('sitemap/indexlanguage').'</loc></sitemap>';
    }
echo '</sitemapindex>';
exit;