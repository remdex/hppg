/*
* Small C program for getting image most popular collor palete items in image
*
* Dependency 
* It depends on opencv library
* http://opencv.willowgarage.com/wiki/
*/

/**************************************/
How to compile?

cd bin/color_indexer/
./compile.sh


/**************************************/
How to use it?

Usage: color_indexer <image-file-name> <pallete-file-name> <threshold>

Example
./bin/color_indexer/color_indexer bin/color_indexer/forest.jpg doc/color_palletes/color_pallete.txt 25

Expected output:
1. Left variable represents pallete_id
2. Represents how many times color item was matched

<pallete_id>-<count>

10-1477
11-1193
12-391
13-357
14-186
21-59
30-764
40-1345
41-180
48-39
49-95
50-1579
51-308
52-256
60-1553
61-138
62-190
63-89
69-35
70-1229
71-115
72-174
73-179
78-42
79-95
80-1037
81-80
82-120
83-96
89-35
90-547
91-159
100-347
101-59
107-46
110-104
113-34
114-45
115-75
116-167
117-235
118-73
119-46

/**************************************/
How to use it in php enviroment?

Example:
./bin/color_indexer/color_indexer bin/color_indexer/forest.jpg doc/color_palletes/color_pallete.txt 25 > image_stats.txt

/**************************************/
What is collor_pallete.txt file structure?
<pallete_id>;<red>;<green>;<blue>
1;213;245;254;20
2;174;235;253;40

Last item is position, and it's optional