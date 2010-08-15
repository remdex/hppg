<?php

class lhPaginator{
    
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $default_ipp = 20;
	var $querystring;
    var $translationContext;
    var $serverURL;
	
	function Paginator()
	{
		$this->current_page = 1;
		$this->mid_range = 7;
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
	}

	function paginate()
	{
		if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
		$this->num_pages = ceil($this->items_total/$this->items_per_page);
		
		$url = erLhcoreClassURL::getInstance();
				
		$this->current_page = $url->getParam('page') !== null ? (int)$url->getParam('page') : 0; // must be numeric > 0
		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;
	
		$this->return = ($this->current_page != 1) ? "<a class=\"previous\" href=\"$this->serverURL/(page)/$prev_page$this->querystring\">".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Previous')."</a>":'';

		$this->mid_range = 7;
			
		if($this->num_pages > 10)
		{		

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			
			$this->range = range($this->start_range,$this->end_range);
						
			end($this->range);
			$lastArrayNumber = current($this->range);
            $needNoBolder = false;
					
			if ($this->range[0] > 1)
			{
				$i = 1;
				$pageURL = $i > 1 ? '/(page)/'.$i : '';
				$this->return .= ($i == $this->current_page) ? "<a title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" class=\"current no-b\" href=\"#\">$i</a>":"<a class=\"no-b\" title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" href=\"{$this->serverURL}{$pageURL}{$this->querystring}\">$i</a>";
				$this->return .= " ... ";
				$needNoBolder = true;
			}
			
			for($i=$this->range[0];$i<=$lastArrayNumber;$i++)
			{	
			    if 	($i > 0) {
				$pageURL = $i > 1 ? '/(page)/'.$i : '';
				$noBolderClass = ($i == 1 || $needNoBolder == true) ? ' no-b' : '';
				$needNoBolder = false;
				$this->return .= ($i == $this->current_page) ? "<a title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" class=\"current{$noBolderClass}\" href=\"#\">$i</a>":"<a class=\"{$noBolderClass}\" title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" href=\"{$this->serverURL}{$pageURL}{$this->querystring}\">$i</a>";
			    }
			}
			
			if ($lastArrayNumber < $this->num_pages)
			{
				$this->return .= " ... ";
				$i = $this->num_pages;
				$pageURL = '/(page)/'.$i;
				$this->return .= ($i == $this->current_page) ? "<a title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" class=\"current no-b\" href=\"#\">$i</a>":"<a class=\"no-b\" title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" href=\"{$this->serverURL}{$pageURL}{$this->querystring}\">$i</a>";
			}			
			
		}
		else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
			    $noBolderClass = ($i == 1) ? ' no-b' : '';
				$this->return .= ($i == $this->current_page) ? "<a class=\"current{$noBolderClass}\" href=\"#\">$i</a> ":"<a class=\"paginate\" href=\"$this->serverURL/(page)/$i$this->querystring\">$i</a>";
			}			
		}	
				
		$this->return .= (($this->current_page != $this->num_pages)) ? "<a class=\"next\" href=\"$this->serverURL/(page)/$next_page$this->querystring\">".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Next')."</a>":"";
		
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
	}

	function display_pages()
	{
		return $this->return;
	}
}