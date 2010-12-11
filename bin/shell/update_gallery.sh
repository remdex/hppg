#!/bin/sh

if [ ! -f "cron.php" -a \
     ! -f "index.php" -a \
     ! -f "runtest.php" -a \
     ! -d "design" -a \
     ! -d "lib" -a \
     ! -d "pos" -a \
     ! -d "modules" ] ; then
     echo "You seem to be in the wrong directory"
     echo "Place yourself in the HPPG root directory and run ./bin/shell/update_gallery.sh"
     exit 1
fi

echo "Updating design directory using SVN"
svn update "./design"

echo "Updating doc files"
svn update "./doc"

echo "Updating eZComponents"
svn update "./ezcomponents"

echo "Updating library files"
svn update "./lib"

echo "Updating modules"
svn update "./modules"

echo "Updating pos files"
svn update "./pos"

echo "Updating translations"
svn update "./translations"

echo "Updating bin php files"
svn update "./bin/php"

echo "Updating Zend Framework"
svn update "./Zend"

echo "Updating var directory"
svn update "./var"

echo "Clearing cache"
php ./bin/php/clear_cache.php