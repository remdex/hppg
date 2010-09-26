<?php

class erLhcoreClassModelGalleryDelayImageHit {
        
   public function getState()
   {
       return array(
               'pid'            => $this->pid,
               'mtime'      => $this->mtime            
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function addHit($pid) 
   {
   	   $db = ezcDbInstance::get();
       $stmt = $db->prepare('INSERT DELAYED INTO lh_delay_image_hit VALUES (:pid,:mtime)');
       $stmt->bindValue( ':pid',$pid);       
       $stmt->bindValue( ':mtime',time());       
       $stmt->execute();
   }
   
   public static function updateMainCounter()
   {
   		$db = ezcDbInstance::get();   		
	    $stmt = $db->prepare('CREATE TEMPORARY TABLE tmp_counter (
		    pid INT(10) UNSIGNED NOT NULL,
		    total INT(3) UNSIGNED NOT NULL,
		    mtime INT(11) UNSIGNED NOT NULL
		) ENGINE=MEMORY;
			
		INSERT INTO 
		      lh_gallery_popular24 (
		        SELECT
		            lh_delay_image_hit.pid,
		            COUNT(lh_delay_image_hit.pid),
		            UNIX_TIMESTAMP()
		        FROM
		            lh_delay_image_hit
		        GROUP BY
		            lh_delay_image_hit.pid
		      )
       ON DUPLICATE KEY UPDATE hits = hits + VALUES(hits);
       
	   LOCK TABLE lh_delay_image_hit WRITE;
	   INSERT INTO
		    tmp_counter (
		        SELECT
		            lh_delay_image_hit.pid,
		            COUNT(lh_delay_image_hit.pid),
		            MAX(lh_delay_image_hit.mtime)
		        FROM
		            lh_delay_image_hit
		        GROUP BY
		            lh_delay_image_hit.pid
		    );	
       
		DELETE FROM lh_delay_image_hit;
				
		UNLOCK TABLE;
		UPDATE LOW_PRIORITY
		    lh_gallery_images AS c
		INNER JOIN
		    tmp_counter AS tc
		ON(
		    c.pid = tc.pid
		)
		SET
		    c.hits = (
		        c.hits + tc.total
		    ),
		    c.mtime = tc.mtime
		;');
	    $stmt->execute();
   }
   
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'image':
   				
   				try {
   					$this->image = erLhcoreClassGallery::getSession()->load( 'erLhcoreClassModelGalleryImage', (int)$this->pid );
   				} catch (Exception $e) {
   					$this->image = false;
   				}
   				return $this->image;
   				break;
   		
   			default:
   				break;
   		}
   }
   
   public $pid = 0;
   public $mtime = null;
   
}

?>