<?php
namespace Zendvn\View\Helper;
class GoogleMapImage{
	
	public static function create($name = '', $value = '', $attr = array(), $options = null){
	    
	    $bounds = json_decode($value['bounds'],true);
	    $zoom = array_keys($bounds);
	    $max = current($zoom);
	    $min = end($zoom);
	    $avg = (int)(array_sum($zoom) / count($zoom));
	    $url = ZENDVN_RES_FILE_URL . '/planning-map/' . $value['id'] .'/';
	    
		$xhtml = '	<script>
                    function zendvnGmapImage() {
                    	var min = ' . $min . ';
                    	var avg = ' . $avg . ';
                    	var max = ' . $max . ';
                    	var url = "' . $url . '";
                    	var ext = "' . self::_setExt($value['image']) . '";
                    	var bounds = JSON.parse(\'' . $value['bounds'] . '\');
                        var map = new google.maps.Map(document.getElementById("' . $name . '"), {
                                    zoom: avg,
                                    minZoom: min,
                                    maxZoom: max,
                            		center: {lat: 0, lng: 0}
                                });
                    	  
                        var imageMapType = new google.maps.ImageMapType({
                                getTileUrl: function(coord, zoom){
                                	if((zoom < min) || (zoom > max) || (bounds[zoom][0][0] > coord.x) || (coord.x > bounds[zoom][0][1]) || (bounds[zoom][1][0] > coord.y) || (coord.y > bounds[zoom][1][1])) {
                              	        return null;
                              	      }
                              	      var result = [url, zoom, "_", coord.x, "_", coord.y, ".", ext].join("");
                              	      return result;
                                },
                                tileSize: new google.maps.Size(256, 256)
                            });
                        map.overlayMapTypes.push(imageMapType);
                    }
                    </script>
                    <script async defer src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=zendvnGmapImage"></script>';
		return $xhtml;
	}
	private function _setExt($image){
	    $image = basename($image);
	    preg_match("'^(.*)\.(gif|jpe?g|png)$'i", $image, $ext);
	    return strtolower($ext[2]);
	}

}
