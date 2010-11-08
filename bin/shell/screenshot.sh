#!/bin/sh

/usr/bin/gnash --hide-menubar -r 1 $1 &

sleep 10
import -window root var/tmpfiles/screen.jpg
convert var/tmpfiles/screen.jpg -fuzz 80% -trim +repage -crop +0+30 var/tmpfiles/screen_cropped.jpg

pkill gnash