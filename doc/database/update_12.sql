CREATE TEMPORARY TABLE tmp_counter (
		    pid INT(10) UNSIGNED NOT NULL,
		    mtime INT(11) UNSIGNED NOT NULL
		) ENGINE=MEMORY;

INSERT INTO
		    tmp_counter (
		        SELECT
		            lh_gallery_comments.pid,		            
		            MAX(UNIX_TIMESTAMP(lh_gallery_comments.msg_date))
		        FROM
		            lh_gallery_comments
		        GROUP BY
		            lh_gallery_comments.pid
		    );


UPDATE lh_gallery_images  

INNER JOIN tmp_counter ON tmp_counter.pid = lh_gallery_images.pid 

SET lh_gallery_images.comtime = tmp_counter.mtime;