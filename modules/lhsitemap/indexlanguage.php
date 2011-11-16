<?php

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/albumindex').'</loc></sitemap>';
echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/categoryindex').'</loc></sitemap>';
echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/imageindex').'</loc></sitemap>';
echo '<sitemap><loc>http://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('sitemap/modules').'</loc></sitemap>';
echo '</sitemapindex>';
exit;