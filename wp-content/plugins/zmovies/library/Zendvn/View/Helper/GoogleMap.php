<?php
namespace Zendvn\View\Helper;
class GoogleMap{
	
	/*
	 * $name 	: Tên của phần tử textboxx
	 * $attr 	: Các thuộc tính của phần tử textbox 
	 * 		   	  Id - style - width - class ...
	 * $options	: Các phần sẽ bổ xung khi phát sinh trường hợp mới
	 *             - type: kiểu của map(embed,marker-info) mặc định là embed
	 */
	
	public static function create($name = '', $value = '', $attr = array(), $options = null){
	      
		$xhtml = '	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        			<script type="text/javascript">
                        (function ( $ ) {
                			// mapId
                    		// mapId-lat
                    		// mapId-lng
                    		// mapId-address
                    		// mapId-zoom
		                    // yeu cau truyen vao mapId, doi voi cac truong hop can luu lai thong tin map
		                    // thi tao cac in put lat,lng,address,zoom de nhan lai gia tri
		                    // cau hinh map xem ham defaults()
                		    $.fn.zendvnGmap = function(options) {
                    		    if(typeof options == "string"){
                    		        options = eval("(" + options + ")");
                    		    }
                			    var _map         = null;
                			    var _marker      = null;
                			    var _geocoder    = new google.maps.Geocoder();
                			    var _infoWindow  = new google.maps.InfoWindow();
                		    	var _options     = $.extend( {}, defaults(), options );
                		        var _mapId         = "' . $name . '";		        
                		        init();
                		        function mapAddress() {
                		        	
                                    _geocoder.geocode( { "address": _options.address}, function(results, status) {
                		                if (status == google.maps.GeocoderStatus.OK && results[0]) {
                                            var formatted_address = results[0].formatted_address;
                                            var location = results[0].geometry.location;
                                            var lat = location.lat();
                                            var lng = location.lng();
                		            
                		                	if(_options.type == "embed"){
                			                    embed(results[0].formatted_address);
                                            }else if(_options.type == "marker-info"){
                                            	_options.address = _options.address == "" ? formatted_address : _options.address;
                                                _options.latLng = {lat : lat, lng : lng};
                		                        markerInfo(_options.latLng);
                                            }
                		                	$("#" + _mapId + "-address").val(_options.address);
                						    $("#" + _mapId + "-lat").val(lat);
                						    $("#" + _mapId + "-lng").val(lng);
                		                } 
                        			});
                            	}
                		        function embed(address) {
                		        	
                	                var url = "https://www.google.com/maps/embed/v1/place?q=";
                	                var key = "AIzaSyDeo3fP8jmQ_1G32c3u8jaj613r28uB-8s";
                                    var iframe = document.createElement("iframe");
                                    iframe.setAttribute("width", "100%");
                                    iframe.setAttribute("height", "100%");
                                    iframe.setAttribute("frameborder", "0");
                                    iframe.setAttribute("style", "border:0");
                                    iframe.setAttribute("src", url + address + "&key=" + key);
                                    iframe.setAttribute("allowfullscreen", "");
                        		    $("#" + _mapId).html(iframe);
                            	}
                    			
                		        function markerInfo(location) {
                		            location.lat = parseFloat(location.lat);
                		            location.lng = parseFloat(location.lng);
                		            var pos = new google.maps.LatLng(location.lat, location.lng);
                                    _map = new google.maps.Map(document.getElementById(_mapId), {
                                                                                        zoom: parseInt(_options.zoom),
                                                                                        center: pos,
                                                                                        mapTypeId: google.maps.MapTypeId.ROADMAP
                                                                                        });
                                    _infoWindow = new google.maps.InfoWindow({content: "<b>Địa chỉ: </b>" + _options.address});
                                    _marker = new google.maps.Marker({
                                                                    map: _map,
                                                                    position: pos,
                                                                    draggable: _options.draggable 
                                                                    });
                                    _infoWindow.open(_map,_marker);
                                    google.maps.event.addListener(_marker, "dragend", function() {
                                        _geocoder.geocode({"latLng": _marker.getPosition()}, function(results, status) {
                                            if (status == google.maps.GeocoderStatus.OK) {
                                                if (results[0]) {
                                                	process(results[0]);
                                                }
                                            }
                                        });
                                    });
                		            if(_options.click){
                                        google.maps.event.addListener(_map, "click", function(event) {
                                            _geocoder.geocode({"latLng": event.latLng}, function(results, status) {
                                                if (status == google.maps.GeocoderStatus.OK) {
                                                    if (results[0]) {
                                                        process(results[0]);
                                                        _marker.setPosition(event.latLng);
                                                    }
                                                }
                                            });
                		                    event.preventBubble=true;
                                        });
                		            }
                		            if(_options.zoom_change){
                                        google.maps.event.addListener(_map, "zoom_changed", function() {
                                            $("#" + _mapId + "-zoom").val(_map.getZoom());
                                        });
                		            }
                    		    }
                		        function defaults() {
                		            return {
                			     		   "type"         : "marker-info",// kiểu của map: embed|marker-info
                			     		   
                		                   "zoom"         : 10, // zoom mặc định
                			     		   "latLng"       : {lat : "", lng : ""}, // Việt Nam
                			     		   "address"      : "", // địa chỉ mặc định
                		                   
                		                   "draggable"    : false, // keo marker lay position, mac dinh ko ho tro
                			     		   "click"        : false, // click chuot lay position, mac dinh ko ho tro
                			     		   "zoom_change"  : false // zoom thay doi zoom, mac dinh ko ho tro
                	    		    		};
                		        }
                			    //=============================================
                				//Ham mac dinh cua Plugin
                				//=============================================
                				function init(){
                					
            						if(_options.type == "embed"){
                    		            if(_options.address == ""){
                    					   _options.address = "Việt Nam";
                    		            }
            							mapAddress();
                                    }else if(_options.type == "marker-info"){
                		                if(_options.latLng.lat == "" || _options.latLng.lng == "" || _options.latLng.lat == null || _options.latLng.lng == null){
                		                    if(_options.address == ""){
                		                        _options.address = "Việt Nam";
                		                        _options.latLng= {lat : 14.058324, lng : 108.277199};
                		                        markerInfo(_options.latLng);
                		                    }else{
                    							mapAddress();
                		                    }
                		                }else{
                    		                if(_options.address == ""){
                        					   _options.address = "Việt Nam";
                        		            }
                		                    markerInfo(_options.latLng);
                		                }
                                    }
                				}
                				
                				function process(results){
                					$("#" + _mapId + "-address").val(results.formatted_address);
                				    $("#" + _mapId + "-lat").val(_marker.getPosition().lat());
                				    $("#" + _mapId + "-lng").val(_marker.getPosition().lng());
                				    _infoWindow.setContent("<b>Địa chỉ: </b>" + results.formatted_address);
                				    _infoWindow.open(_map,_marker);
                				    _map.setCenter(_marker.getPosition());
                				}
                		        return this;   
                		    };
                
                		    $("#' . $name . '").zendvnGmap(' . json_encode($value,JSON_UNESCAPED_UNICODE) . ');
                		}( jQuery ));
        			</script>';
		return $xhtml;
	}

}
