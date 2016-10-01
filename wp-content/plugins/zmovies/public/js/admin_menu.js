jQuery(document).ready(function($){
	zAdminMenu($);
});

/*
 * Hàm xử lý hệ thống menu của plugin
 * 
 */
function zAdminMenu($){
	// mảng các taxonomy và custom taxonomy trong hệ thống menu của plugin
	var _zTax 	= ['category','zvideos_channel','zvideos_playlist','zvideos_youtube_user','zvideos_youtube_keyword'];
	// mảng các post và custom post trong hệ thống menu của plugin
	var _zPType = ['post'];
	
	// Sử dụng global typenow để kiểm tra post_type có nằm trong mảng post cho phép show menu
	if(_zPType.indexOf(typenow) != -1){
		
		var elemt = "#toplevel_page_zvideos-manager";
		
		$(elemt).removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
		$(elemt + ' > a').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
		$(elemt + ' ul > li').removeClass('current');
		$(elemt + ' ul > li > a').removeClass('current');
		
		// lấy taxonomy đang truy cập
		var taxonomy = $('input[name="taxonomy"]').val();
		
		// kiểm tra tax xem có tồn tại trong mảng được cho phép
		if(_zTax.indexOf(taxonomy) != -1){
			var url ='edit-tags.php?taxonomy=' + taxonomy + '&post_type=post';
			// active menu
			$(elemt + " a[href='" + url + "']").addClass('current').parent().addClass('current');
		}else if(typeof taxonomy == 'undefined'){
			// add post
			var _zPStatus = ['report_die', 'report_16'];
			//lấy post_status đang truy cập
			var post_status = $('input[name="post_status"]').val();
			var url ='edit.php';
			if(_zPStatus.indexOf(post_status) != -1){
				url += '?post_status=' + post_status + '&post_type=' + typenow;
			}

			$(elemt + " a[href='" + url + "']").addClass('current').parent().addClass('current');
		}
	}
	
}
