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

class ConvertIcons implements FilterInterface{
	
	public function filter($value){
		
		$iconsList 		= array(
								'icon_devil'		=> '3:)',
								'icon_angel'		=> 'O:)',
								'icon_smile' 		=> ':)',
								'icon_grumpy'		=> '>:(',
								'icon_frown'		=> ':(',
								'icon_tongue'		=> ':P',
								'icon_grin'			=> '=D',
								'icon_upset'		=> '>:o',
								'icon_gasp'			=> ':o',
								'icon_wink'			=> ';)',
								'icon_pacman'		=> ':v',
								'icon_unsure'		=> ':/',
								'icon_cry'			=> ":'(",
								'icon_kiki'			=> '^_^',
								'icon_glasses'		=> '8-)',
								'icon_sunglasses'	=> 'B|',
								'icon_heart'		=> '<3',
								'icon_squint'		=> '-_-',
								'icon_confused'		=> 'o.O',
								'icon_colonthree'	=> ':3',
								'icon_like'			=> '(y)'
								);
		$charaterA = '#(à|ả|ã|á|ạ|ă|ằ|ẳ|ẵ|ắ|ặ|â|ầ|ẩ|ẫ|ấ|ậ)#imsU';
		$replaceCharaterA = 'a';
		$value = preg_replace($charaterA, $replaceCharaterA, $value);
      	
      
		return $value;
	}
}