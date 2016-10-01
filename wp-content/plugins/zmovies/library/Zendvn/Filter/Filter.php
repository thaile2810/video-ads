<?php
/**
 * ============================================================
 *
 * Đàm Quang Dũng
 *
 * @copyright	Copyright(c) 2011-2014 MR.ERROR
 * @license		http://dungdam.zendvn.com
 * @version		28-08-2014 MR.ERROR
 * @since		28-08-2014 12:01:59
 *
 * ============================================================
 */
namespace Zendvn\Filter;

use Zend\Filter\FilterInterface;

class Filter implements FilterInterface{
	
	public function filter($value){
		$filter = new Zend\Filter();
		$filter->addFilter(new Zend\Filter\StringToLower(array('encoding'=>'utf-8')))
				->addFilter(new Zendvn\Filter\Remove())
				->addFilter(new Zend\Filter\Alnum(true))
				->addFilter(new Zend\Filter\PregReplace('#\s+#','-'));
		$value = $filter->filter($value);
		return $value;
	}
	
	public function specialCharacter($value){
		$find[] = 'Ã¢â‚¬Å“';  // left side double smart quote
		$find[] = 'Ã¢â‚¬Â�';  // right side double smart quote
		$find[] = 'Ã¢â‚¬Ëœ';  // left side single smart quote
		$find[] = 'Ã¢â‚¬â„¢';  // right side single smart quote
		$find[] = 'Ã¢â‚¬Â¦';  // elipsis
		$find[] = 'Ã¢â‚¬â€�';  // em dash
		$find[] = 'Ã¢â‚¬â€œ';  // en dash
		$find[] = 'Ã‚â‚¬Å“';  // left side double smart quote
		$find[] = 'Ã‚â‚¬Â�';  // right side double smart quote
		$find[] = 'Ã‚â‚¬Ëœ';  // left side single smart quote
		$find[] = 'Ã‚â‚¬â„¢';  // right side single smart quote
		$find[] = 'Ã‚â‚¬Â¦';  // elipsis
		$find[] = 'Ã‚â‚¬â€�';  // em dash
		$find[] = 'Ã‚â‚¬â€œ';  // en dash
		
		$replace[] = '"';
		$replace[] = '"';
		$replace[] = "'";
		$replace[] = "'";
		$replace[] = "...";
		$replace[] = "-";
		$replace[] = "-";
		
		$value = str_replace($find, $replace, $value);
		return $value;
	}
	
	public function replaceSpecial($str){
		$chunked = str_split($str,1);
		$str = "";
		foreach($chunked as $chunk){
			$num = ord($chunk);
			// Remove non-ascii & non html characters
			if ($num >= 32 && $num <= 123){
				$str.=$chunk;
			}
		}
		return $str;
	}
	
	public static function mysql_regexp_escape_string($str, $options = null){
		 $special_chars = array('*', '.', '?', '+', '[', ']', '(', ')', '{', '}', '^', '$', '|', '\\');
	    $replacements = array();
	
	    foreach ($special_chars as $special_char)
	    {
	        $replacements[] = '\\' . $special_char;
	    }
	
	    return str_replace($special_chars, $replacements, $str);
	}
	
	public static function filterContent($str, $options = null){
		$str = str_replace('<a ', '<a target="_blank" ', $str);
		if($options == null){
			$str = str_replace('\\','',$str);
			$str = str_replace('\\\'','"',$str);
			$str = str_replace('\\\\','',$str);
			$str = str_replace('<pre','<div',$str);
			$str = str_replace('</pre','</div',$str);
		}
		if($options == 'filter'){
			$str = str_replace('\\','',$str);
			$str = str_replace('\\\'','"',$str);
			$str = str_replace('\\\\','',$str);
			$str = str_replace('<pre','<div',$str);
			$str = str_replace('</pre','</div',$str);
			$str = str_replace('<script>','&lt;script&gt;',$str);
			$str = str_replace('<script type="text/javascript">','&lt;script type="text/javascript"&gt;',$str);
			$str = str_replace('</script>','&lt;/script&gt;',$str);
			$str = str_replace('<javascript>','&lt;javascript&gt;',$str);
			$str = str_replace('</javascript>','&lt;/javascript&gt;',$str);
			$str = str_replace('<?','&lt;?',$str);
			$str = str_replace('<?php','&lt;?php',$str);
			$str = str_replace('?>','?&gt;',$str);
		}
		
		return $str;
	}
	
	/**
	 * RÃºt ngáº¯n ná»™i dung bÃ i viáº¿t
	 * @input:	Cong hoa xa hoi chu nghia VN, 4
	 * @output:	Cong...
	 * @param unknown_type $str
	 * @param unknown_type $len
	 * @param unknown_type $charset
	 */
	public static function shortString($str, $len = 200, $charset='UTF-8'){
//		$str = html_entity_decode($str, ENT_QUOTES, $charset);
		$str = strip_tags($str);
		if(mb_strlen($str, $charset)> $len){
			$arr = explode(' ', $str);
			$str = substr($str, 0, $len);
			$arrRes = explode(' ', $str);
			$last = $arr[count($arrRes)-1];
			unset($arr);
			if(strcasecmp($arrRes[count($arrRes)-1], $last)){
				unset($arrRes[count($arrRes)-1]);
			}
			return implode(' ', $arrRes)."...";
		}
		return $str;
	}
	
	public static function cutString($str, $len = 200, $charset='UTF-8'){
//		$str = html_entity_decode($str, ENT_QUOTES, $charset);
//		if(mb_strlen($str, $charset)> $len){
//			$str = substr($str, 0, $len) . '...';
//		}
		return $str;
	}
	
	public static function hideString($str, $len = 200, $options = null, $charset='UTF-8'){
		$str = str_replace('<br/>', '<br>', $str);
		$str = str_replace('<br />', '<br>', $str);
		
		
		if($options['strip_tag'] == true){
			$str = strip_tags($str);
		}
		
		//=================== Proccessing HTML Tag =======================
		$tmpStr 		= mb_substr($str, $len, mb_strlen($str, $charset),$charset);
		
		$pos_start_a 	= strpos($tmpStr, '<a') + $len;
		$pos_end_a 		= strpos($tmpStr, '</a>') + $len;
		
		$moreLen = 0;
		
		if(strpos($str, '</a>') + 5 < $len && strpos($str, '</a>') > 0){
			$len = strpos($str, '</a>');
			$moreLen = 5;
		}else{
			if(strpos($str, '<a') > 10 && strpos($str, '<a') < $len ){
				$len = strpos($str, '<a');
			}
			$moreLen = 3;
		}
		
		
		if(mb_strlen($str, $charset) > $len + $moreLen){
			$id = rand(5, 9999) . time();
			$newStr = mb_substr($str, 0, $len, $charset);
			$lastStr = mb_substr($str, $len, mb_strlen($str, $charset),$charset);
			$newStr = $newStr . '<span class="text_exposed_hide">...</span>';
			$newStr = $newStr . '<span class="text_exposed_show">' . $lastStr . '</span>';
			$newStr = $newStr . '<a class="text_exposed_show link_hide" id="' . $id . '" onclick="hideString(this);" href="javascript:void(0)">See Less</a>';
			$newStr = $newStr . '<span class="text_exposed_link"><a id="' . $id . '" onclick="showString(this);" href="javascript:void(0)">See More</a></span>';
		}else{
			$newStr = $str;
		}
		return $newStr;
	}
}