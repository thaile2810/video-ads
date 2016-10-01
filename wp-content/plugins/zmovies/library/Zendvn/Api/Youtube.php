<?php
namespace Zendvn\Api;

class Youtube{
    
    private $_api_key = 'AIzaSyAmFfsKvd4k83ONyHqpT65NQaD9BXFqico';
    
    private $_option = ['channels','playlists','playlistItems'];
    
    private $_url   = 'https://www.googleapis.com/youtube/v3/';
    
    public function __construct(){
        
    }
    
    public function channel($channelId, $pageToken = null,$maxResults = 50){
        //$url = $this->_url . 'playlists?part=snippet&fields=items(id,kind,snippet/title),nextPageToken&key=' . $this->_api_key . '&channelId=' . $channelId;
        
        $query  = array(
                    'part'      => 'snippet',
                    'fields'    => 'items(id,kind,snippet/title),nextPageToken',
                    'key'       => $this->_api_key,
                    'channelId' => $channelId,
                );
        
        if($pageToken != null){
            $query['pageToken'] = $pageToken;
        }
        
        $url = $this->_url . 'playlists?' . http_build_query($query);
        
        return $this->listData($url,$maxResults);
    }
    
    public function playlist($channelId){
        //$url = $this->_url . 'playlists?part=snippet&key=' . $this->_api_key . '&channelId=' . $channelId;
        
        $query  = array(
                        'part'      => 'snippet',
                        'key'       => $this->_api_key,
                        'channelId' => $channelId,
                        );
        $url = $this->_url . 'playlists?' . http_build_query($query);
        return $this->listData($url);
    }
    
    public function user($username){
        //$username   = str_replace('https://www.youtube.com/user/', '', $username);
        //$url        = $this->_url . 'channels?part=snippet&fields=items(id,kind,snippet/title),nextPageToken&key=' . $this->_api_key . '&forUsername=' . $username;
        
        $query  = array(
                        'part'          => 'snippet',
                        'fields'        => 'items(id,kind,snippet/title),nextPageToken',
                        'key'           => $this->_api_key,
                        'forUsername'   => str_replace('https://www.youtube.com/user/', '', $username),
                        );
        $url = $this->_url . 'channels?' . http_build_query($query);
        
        return $this->listData($url,1);
    }
    
    public function playlistItems($playlistId, $pageToken = null,$maxResults = 50){
        /* $url = $this->_url . 'playlistItems?part=snippet&fields=items(snippet/resourceId/videoId),nextPageToken&key=' . $this->_api_key . '&playlistId=' . $playlistId;
        if($pageToken != null){
            $url .= '&pageToken=' . $pageToken;
        } */
        
        $query  = array(
                        'part'          => 'snippet',
                        'fields'        => 'items(snippet/resourceId/videoId),nextPageToken',
                        'key'           => $this->_api_key,
                        'playlistId'    => $playlistId,
                        );
        
        if($pageToken != null){
            $query['pageToken'] = $pageToken;
        }
        
        $url = $this->_url . 'playlistItems?' . http_build_query($query);
        
        return $this->listData($url,$maxResults);
    }
    public function video($videoId, $fields = ''){
        /* $url = $this->_url . 'videos?part=snippet&key=' . $this->_api_key . '&maxResults=1&id=' . $videoId;
        
        if(!empty($fields)){
            $url .= '&fields=' . $fields;
        } */
        
        
        $query  = array(
                        'part'          => 'snippet',
                        'key'           => $this->_api_key,
                        'maxResults'    => 1,
                        'id'            => $videoId,
                        );
        
        if(!empty($fields)){
            $query['fields'] = $fields;
        }
        
        $url = $this->_url . 'videos?' . http_build_query($query);
        
        return $this->getContent($url);
    }
    public function videoCategories($id){
        /* $url = $this->_url . 'videoCategories?part=snippet&key=' . $this->_api_key . '&id=' . $id;
        $url .= '&fields=items(id,snippet/title)'; */
        
        $query  = array(
                        'part'          => 'snippet',
                        'key'           => $this->_api_key,
                        'fields'        => 'items(id,snippet/title)',
                        'id'            => $id,
                        );
        
        $url = $this->_url . 'videoCategories?' . http_build_query($query);
        
        return $this->listData($url,1);
    }
    public function videoTags($videoId){
        //$url = $this->_url . 'videos?part=snippet&fields=items(snippet/tags)&key=' . $this->_api_key . '&maxResults=1&id=' . $videoId;
        
        $query  = array(
            'part'          => 'snippet',
            'key'           => $this->_api_key,
            'fields'        => 'items(snippet/tags)',
            'maxResults'    => 1,
            'id'            => $videoId,
        );
        
        $url = $this->_url . 'videos?' . http_build_query($query);
        
        return $this->getContent($url);
    }
    public function listData($url, $max = 50){
        $content    = file_get_contents($url . '&maxResults=' . $max);
        return json_decode($content,true);
    }
    
    public function search($keyword, $maxResults = 50, $order = 'date'){
        
        $order = empty($order) ? 'date' : $order;
        
        $url = $this->_url . 'search?' . http_build_query(
                                            array(
                                                'part' => 'snippet',
                                                'fields' => 'items(id/videoId)',
                                                'key' => $this->_api_key,
                                                'order' => $order,
                                                'q' => $keyword,
                                                'maxResults' => $maxResults,
                                                'regionCode' => 'US',
                                                'relevanceLanguage' => 'en',
                                            )
                                        );
        return $this->getContent($url);
    }
    
    public function isYoutubeId($videoId){
        //$url = $this->_url . 'videos?part=id&key=' . $this->_api_key . '&maxResults=1&id=' . $videoId;
        
        $query  = array(
            'part'          => 'id',
            'key'           => $this->_api_key,
            'maxResults'    => 1,
            'id'            => $videoId,
        );
        
        $url = $this->_url . 'videos?' . http_build_query($query);
        
        $videos = @file_get_contents($url);
        return @json_decode($videos,true);
    }
    
    public function getContent($url){
        $handle = fopen($url, "r");
        $json = '';
        if ($handle) {
            while (($buffer = fgets($handle, 1024)) !== false) {
                $json .= $buffer;
            }
            fclose($handle);
        }
        //$json = file_get_contents($url);
        return @json_decode($json,true);
    }
}