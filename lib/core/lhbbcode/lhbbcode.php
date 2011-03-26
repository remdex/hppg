<?php

class erLhcoreClassBBCode
{       
    // From WP, that's why we love open source :)
    public static function _make_url_clickable_cb($matches) {
    	$url = $matches[2];
    	$suffix = '';
    
    	/** Include parentheses in the URL only if paired **/
    	while ( substr_count( $url, '(' ) < substr_count( $url, ')' ) ) {
    		$suffix = strrchr( $url, ')' ) . $suffix;
    		$url = substr( $url, 0, strrpos( $url, ')' ) );
    	}
    
    	if ( empty($url) )
    		return $matches[0];
    
    	return $matches[1] . "<a href=\"$url\" class=\"link\" target=\"_blank\">$url</a>" . $suffix;
   }

   public static function BBCode2Html($text) {
    	$text = trim($text);
        	    
    	// Smileys to find...
    	$in = array( 	 ':)', 	
    					 ':D',
    					 ':(',
    					 ':o',
    					 ':p',
    					 ';)'
    	);
    	
    	// And replace them by...
    	$out = array(	 '<img alt=":)" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_smile.png').'" />',
    	                 '<img alt=":D" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_happy.png').'" />',
    					 '<img alt=":(" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_unhappy.png').'" />',
    					 '<img alt=":o" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_surprised.png').'" />',
    					 '<img alt=":p" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_tongue.png').'" />',
    					 '<img alt=";)" src="'.erLhcoreClassDesign::design('js/markitup/sets/bbcode/images/smileys/emoticon_wink.png').'" />'
    	);
    	$text = str_replace($in, $out, $text);
    	
    	// BBCode to find...
    	$in = array( 	 '/\[b\](.*?)\[\/b\]/ms',	
    					 '/\[i\](.*?)\[\/i\]/ms',
    					 '/\[u\](.*?)\[\/u\]/ms',
    					/* '/\[img\](.*?)\[\/img\]/ms',
    					 '/\[email\](.*?)\[\/email\]/ms',
    					 '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
    					 '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
    					 '/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',*/
    					 '/\[quote](.*?)\[\/quote\]/ms',
    					 '/\[list\=(.*?)\](.*?)\[\/list\]/ms',
    					 '/\[list\](.*?)\[\/list\]/ms',
    					 '/\[\*\]\s?(.*?)\n/ms'
    	);
    	// And replace them by...
    	$out = array(	 '<strong>\1</strong>',
    					 '<em>\1</em>',
    					 '<u>\1</u>',
    					 /*'<img src="\1" alt="\1" />',
    					 '<a href="mailto:\1">\1</a>',
    					 '<a href="\1">\2</a>',
    					 '<span style="font-size:\1%">\2</span>',
    					 '<span style="color:\1">\2</span>',*/
    					 '<blockquote>\1</blockquote>',
    					 '<ol start="\1">\2</ol>',
    					 '<ul>\1</ul>',
    					 '<li>\1</li>'
    	);
    	$text = preg_replace($in, $out, $text);
    		
    	// paragraphs
    	$text = str_replace("\r", "", $text);
    	//$text = "<p>".preg_replace("/(\n){2,}/", "</p><p>", $text)."</p>";
    	$text = nl2br($text);
    	
    	// clean some tags to remain strict
    	// not very elegant, but it works. No time to do better ;)
    	if (!function_exists('removeBr')) {
    		function removeBr($s) {
    			return str_replace("<br />", "", $s[0]);
    		}
    	}
    	
    	$text = preg_replace_callback('/<pre>(.*?)<\/pre>/ms', "removeBr", $text);
    	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
    	
    	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
    	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
    	
    	return $text;
    }

   // From WP :)
   public static function _make_web_ftp_clickable_cb($matches) {
    	$ret = '';
    	$dest = $matches[2];
    	$dest = 'http://' . $dest;
    	if ( empty($dest) )
    		return $matches[0];
    
    	// removed trailing [.,;:)] from URL
    	if ( in_array( substr($dest, -1), array('.', ',', ';', ':', ')') ) === true ) {
    		$ret = substr($dest, -1);
    		$dest = substr($dest, 0, strlen($dest)-1);
    	}
    	return $matches[1] . "<a href=\"$dest\" class=\"link\" target=\"_blank\">$dest</a>$ret";
   }
    
   // From WP :)
   public static function _make_email_clickable_cb($matches) {
    	$email = $matches[2] . '@' . $matches[3];
    	return $matches[1] . "<a href=\"mailto:$email\" class=\"mail\">$email</a>";
   }
   
   public static function _make_paypal_button($matches){
       
         if (filter_var($matches[1],FILTER_VALIDATE_EMAIL)) {            
            return '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations">
            <input type="hidden" name="business" value="'.$matches[1].'">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
            <input type="image" title="Support an artist" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110306-1/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>';
        } else {
            return $matches[0];
        }
   } 
   
   public static function _make_youtube_block($matches) {     
         
         $data = parse_url($matches[1]);
         
         if (isset($data['query'])){
             parse_str($data['query'],$query);                           
             if (stristr($data['host'],'youtube.com') && isset($query['v']) && ($query['v'] != '')) {             
                 return '<iframe title="YouTube video player" width="480" height="300" src="http://www.youtube.com/embed/'.urlencode($query['v']).'" frameborder="0" allowfullscreen></iframe>';             
             } else {
                 return $matches[0]; 
             }
         } else {
             return $matches[0]; 
         }
   }

   // From WP :)
   public static function make_clickable($ret) {
    	$ret = ' ' . $ret;
    	// in testing, using arrays here was found to be faster
    	$ret = preg_replace_callback('#(?<!=[\'"])(?<=[*\')+.,;:!&$\s>])(\()?([\w]+?://(?:[\w\\x80-\\xff\#%~/?@\[\]-]|[\'*(+.,;:!=&$](?![\b\)]|(\))?([\s]|$))|(?(1)\)(?![\s<.,;:]|$)|\)))+)#is', 'erLhcoreClassBBCode::_make_url_clickable_cb', $ret);
    	$ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]+)#is', 'erLhcoreClassBBCode::_make_web_ftp_clickable_cb', $ret);
    	$ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', 'erLhcoreClassBBCode::_make_email_clickable_cb', $ret);

    	// this one is not in an array because we need it to run last, for cleanup of accidental links within links
    	$ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    	
    	$ret = self::BBCode2Html($ret);
    	
    	// Paypal button
    	$ret = preg_replace_callback('#\[paypal\](.*?)\[/paypal\]#is', 'erLhcoreClassBBCode::_make_paypal_button', $ret);

    	// Youtube block
    	$ret = preg_replace_callback('#\[youtube\](.*?)\[/youtube\]#is', 'erLhcoreClassBBCode::_make_youtube_block', $ret);

    	
    	
    	$ret = trim($ret);
    	return $ret;
   }
}


?>