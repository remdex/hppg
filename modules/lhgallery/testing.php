<?php

//$totalPhotos = erLhcoreClassGallery::searchSphinx(array('custom_filter' => array('filter_name' => 'myfilter','filter' => '*, hits=10 AS myfilter'),'filtergt' => array('@relevance' => '1'),'SearchLimit' => 2,'keyword' => 'x-men','sort' => 'hits DESC, @id ASC')); 

echo '<pre>';
//print_r($totalPhotos);
echo '</pre>';
//http://hentai-wallpapers.com/Cyberbabes-and-girls/ecchi/c-106911p.html/(mode)/popular

//$session = erLhcoreClassGallery::getSession();
//$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
////$q->where( $q->expr->gt( 'hits', $q->bindValue( 8 ) ). ' OR '.$q->expr->eq( 'hits', $q->bindValue( 8 ) ).' AND '.$q->expr->lt( 'pid', $q->bindValue( 264437 ) ) )
//$q->orderBy('hits DESC, pid DESC')
//->limit( 2 );
//$objects = $session->find( $q, 'erLhcoreClassModelGalleryImage' );


//$q = $session->createFindQuery( 'erLhcoreClassModelGalleryImage' );
//
//$q2 = $q->subSelect();
//$q2->select( 'pid' )->from( 'lh_gallery_images' );
//$q2->orderBy('mtime DESC , pid DESC');
//$q2->limit(10,120005);
//$q->innerJoin( $q->alias( $q2, 'items' ), 'lh_gallery_images.pid', 'items.pid' );
//
//$objects = $session->find( $q, 'erLhcoreClassModelGalleryImage' );


//print_r($q->__toString());


//print_r($objects);

//
//SELECT lh_gallery_images . *
//FROM lh_gallery_images
//INNER JOIN (
//SELECT pid
//FROM lh_gallery_images
//ORDER BY mtime DESC , pid DESC
//LIMIT 120005 , 10
//) AS items ON items.pid = lh_gallery_images.pid 




//->innerJoin( 't2', 't1.id', 't2.id' );


//$q2 = $q->subSelect();
// $q2->select( 'lastname' )->from( 'users' );
 
 // Use the created subselect to generate the full SQL:
//$q->select( '*' )->from( 'Greetings' );
// ->where( $q->expr->gt( 'age', 10 ),
//            $q->expr->in( 'user', $q2 ) );
  
//$stmt = $q->prepare(); // $stmt is a normal PDOStatement
//  $stmt->execute();


//$items = erLhcoreClassModelGalleryImage::getImages(array('disable_sql_cache' => true,'sort' => 'comtime DESC, pid DESC','offset' => $pages->low, 'limit' => $pages->items_per_page));


//$photos = $stmt->fetchAll();

# $q = $session->createFindQuery( 'Person' );
# $q->where( $q->expr->gt( 'age', $q->bindValue( 15 ) ) )
# ->orderBy( 'full_name' )
# ->limit( 10 );

//$objects = erLhcoreClassGallery::getSession()->find( $stmt, 'erLhcoreClassModelGalleryImage' ); 

//print_r($objects);

$Result['content'] ='';

?>