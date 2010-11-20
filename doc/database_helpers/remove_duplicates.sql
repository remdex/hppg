UPDATE lh_gallery_searchhistory SET crc32 = CRC32( keyword ) ;

ALTER IGNORE TABLE lh_gallery_searchhistory
ADD PRIMARY KEY (keyword);


SELECT COUNT( * ) AS n, keyword
FROM  `lh_gallery_searchhistory` 
GROUP BY keyword
HAVING (
n
) >1
LIMIT 0 , 30;
