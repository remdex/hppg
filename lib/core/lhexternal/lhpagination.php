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
		$this->mid_range = 16;
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
	
		$this->return = ($this->current_page != 1) ? "<a class=\"paginate\" href=\"$this->serverURL/(page)/$prev_page$this->querystring\">&laquo; ".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Previous')."</a> ":"<span class=\"inactive\" href=\"#\">&laquo; ".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Previous')."</span> ";

		$this->mid_range = 16;
			
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
						
			
			
			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
				    $pageURL = $i > 1 ? '/(page)/'.$i : '';
					$this->return .= ($i == $this->current_page) ? "<a title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,"Go to page %item of %numpages",array('item' => $i,'numpages' => $this->num_pages))."\" href=\"{$this->serverURL}{$pageURL}{$this->querystring}\">$i</a> ";
				}
				if (isset($this->range[$this->mid_range-1]))
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
			}
			
		}
		else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
				$this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" href=\"$this->serverURL/(page)/$i$this->querystring\">$i</a> ";
			}			
		}	
		
		$this->return .= (($this->current_page != $this->num_pages)) ? "<a class=\"paginate\" href=\"$this->serverURL/(page)/$next_page$this->querystring\">".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Next')." &raquo;</a>\n":"<span class=\"inactive\" href=\"#\">".erTranslationClassLhTranslation::getInstance()->getTranslation($this->translationContext,'Next')." &raquo;</span>\n";
		
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
	}

	function display_items_per_page()
	{
		$items = '';
		$ipp_array = array(10,25,50,100,'All');
		foreach($ipp_array as $ipp_opt)	$items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$this->serverURL?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
	}

	function display_jump_menu()
	{
	    $option = '';
		for($i=1;$i<=$this->num_pages;$i++)
		{
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		return "<span class=\"paginate\">Jump to page: </span><select class=\"paginate\" onchange=\"window.location='$this->serverURL?page='+this[this.selectedIndex].value+'$this->querystring';return false\">$option</select>\n";
	}

	function display_pages()
	{
		return $this->return;
	}
}