<?php

$modulesURL = array();

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('/'),
    'changefreq' => 'daily',
    'priority' => '1',
    'lastmod' => date('Y-m-d'),
);
	
$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/popular'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/toprated'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/lastuploads'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/lasthits'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/lastcommented'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/lastrated'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/popularrecent'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/ratedrecent'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/search'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('gallery/color'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('similar/image'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

$modulesURL[] = array (
    'url' => erLhcoreClassDesign::baseurl('similar/sketch'),
    'changefreq' => 'daily',
    'priority' => '0.8',
    'lastmod' => date('Y-m-d'),
);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>  
      <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

       foreach ($modulesURL as $moduleItem){
           echo '<url>
				<loc>http://'.$_SERVER['HTTP_HOST'].$moduleItem['url'].'</loc>
				<lastmod>'.$moduleItem['lastmod'].'</lastmod>
				<changefreq>'.$moduleItem['changefreq'].'</changefreq>
				<priority>'.$moduleItem['priority'].'</priority>
				</url>';
       }
echo '</urlset>';
exit;


exit;