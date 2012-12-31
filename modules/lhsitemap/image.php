<?php

$part = (int)$Params['user_parameters']['part_id'];
$offset = (int)$part * erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page');

$images = erLhcoreClassModelGalleryImage::getImages(array(
'limit' => erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_per_page'),
'ignore_fields' => array('filesize','total_filesize','ctime','owner_id','pic_rating','votes','caption','keywords','pic_raw_ip','approved','mtime','comtime','anaglyph','rtime'),
'offset' => $offset,
'smart_select' => true,
'disable_sql_cache' => true,
'sort' => 'pid ASC',
'filter' => array('approved' => 1)));

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
      foreach ($images as $image) {
        echo '<url><loc>';
        echo 'http://'.$_SERVER['HTTP_HOST']. $image->url_path;
        echo '</loc><changefreq>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_frequency').'</changefreq>
        <priority>'.erConfigClassLhConfig::getInstance()->getSetting('sitemap_settings','image_priority').'</priority>
        </url>';
      }
echo '</urlset>';
exit;