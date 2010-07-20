<?

class erLhcoreClassModelArticleStatic {
        
   public function getState()
   {
       return array (
               'id'                  => $this->id,
               'content'             => $this->content,
               'name'            	 => $this->name
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   } 
   
   public static function fetch($id)
   {       
       $articleObj = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleStatic', $id );     
       return $articleObj;
   }
   
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'content_front':   					   					
   					$this->content_front = $this->content;
   					
   					$mathcesLinks = array();
   					preg_match_all('/<a href="lh:(.*?)">(.*?)<\/a>/i',$this->content_front,$mathcesLinks);
   					   					
   					foreach ($mathcesLinks[1] as $key => $matchUrl)
   					{
   						$this->content_front = str_replace('lh:'.$matchUrl,erLhcoreClassDesign::baseurl($matchUrl),$this->content_front);
   					}
   				   	   					   									
   					return $this->content_front;
   					  					
   				break;
   		
   			default:
   				break;
   		}
   }
   
   public static function getArticles($paramsSearch = array())
   {             
	   	$paramsDefault = array('limit' => 32, 'offset' => 0);
	
	   	$params = array_merge($paramsDefault,$paramsSearch);
	
	   	$session = erLhcoreClassArticle::getSession();
	   	$q = $session->createFindQuery( 'erLhcoreClassModelArticleStatic' );
	
	   	$conditions = array();
	
	   	if (isset($params['filter']) && count($params['filter']) > 0)
	   	{
	   		foreach ($params['filter'] as $field => $fieldValue)
	   		{
	   			$conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
	   		}
	   	}
	
	   	if (isset($params['filterin']) && count($params['filterin']) > 0)
	   	{
	   		foreach ($params['filterin'] as $field => $fieldValue)
	   		{
	   			$conditions[] = $q->expr->in( $field, $q->bindValue($fieldValue ));
	   		}
	   	}
	
	   	if (isset($params['filterlt']) && count($params['filterlt']) > 0)
	   	{
	   		foreach ($params['filterlt'] as $field => $fieldValue)
	   		{
	   			$conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue ));
	   		}
	   	}
	
	   	if (isset($params['filtergt']) && count($params['filtergt']) > 0)
	   	{
	   		foreach ($params['filtergt'] as $field => $fieldValue)
	   		{
	   			$conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue ));
	   		}
	   	}
	
	   	if (count($conditions) > 0)
	   	{
	   		$q->where(
	   		$conditions
	   		);
	   	}
	
	   	$q->limit($params['limit'],$params['offset']);
	
	   	$q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' );
	
	   	$objects = $session->find( $q, 'erLhcoreClassModelArticleStatic' );
	
	   	return $objects;
   }
     
   public $id = null;
   public $content = ''; 
   public $name  = '';
}

?>