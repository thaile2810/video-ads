<?php
namespace Zendvn\View\Helper;

class CmsInfoUser{
	
	public function create($avatar, $linkEdit, $username, $fullName,$options = null){
		if(!empty($avatar)){
			$avatarURL	= ZENDVN_FILE_URL . '/users/thumb/' . $avatar;
		}else{
			$avatarURL	= ZENDVN_FILE_URL . '/no-image/no-avatar.png';
		}
		
		return sprintf('<div class="user-panel" style="text-align:left">
							<div class="pull-left image">
								<img src="%s" class="img-circle" alt="User Image">
							</div>
							<div class="pull-left info">
								<p><a href="%s">%s</a></p>
								<span>%s</span>
							</div>
						</div>', $avatarURL, $linkEdit, $username ,$fullName);
	}
}