<?php
namespace Zendvn\Google;

class Playlist{
    
    private $_youtube;
    
    public function __construct($youtube){
        
        // 1. Create the snippet for the playlist. Set its title and description.
        $playlistSnippet = new \Google_Service_YouTube_PlaylistSnippet();
        $playlistSnippet->setTitle('Test Playlist  ' . date("Y-m-d H:i:s"));
        $playlistSnippet->setDescription('A private playlist created with the YouTube API v3');
        
        // 2. Define the playlist's status.
        $playlistStatus = new \Google_Service_YouTube_PlaylistStatus();
        $playlistStatus->setPrivacyStatus('private');
        
        // 3. Define a playlist resource and associate the snippet and status
        // defined above with that resource.
        $youTubePlaylist = new \Google_Service_YouTube_Playlist();
        $youTubePlaylist->setSnippet($playlistSnippet);
        $youTubePlaylist->setStatus($playlistStatus);
        
        // 4. Call the playlists.insert method to create the playlist. The API
        // response will contain information about the new playlist.
        $playlistResponse   = $youtube->playlists->insert('snippet,status', $youTubePlaylist, array());
        $playlistId         = $playlistResponse['id'];
    }
    
    public function tmp($youtube, $playlistId){
       
        
        
        // 5. Add a video to the playlist. First, define the resource being added
        // to the playlist by setting its video ID and kind.
        $resourceId = new \Google_Service_YouTube_ResourceId();
        $resourceId->setVideoId('SZj6rAYkYOg');
        $resourceId->setKind('youtube#video');
        
        // Then define a snippet for the playlist item. Set the playlist item's
        // title if you want to display a different value than the title of the
        // video being added. Add the resource ID and the playlist ID retrieved
        // in step 4 to the snippet as well.
        $playlistItemSnippet = new \Google_Service_YouTube_PlaylistItemSnippet();
        $playlistItemSnippet->setTitle('First video in the test playlist');
        $playlistItemSnippet->setPlaylistId($playlistId);
        $playlistItemSnippet->setResourceId($resourceId);
        
        // Finally, create a playlistItem resource and add the snippet to the
        // resource, then call the playlistItems.insert method to add the playlist
        // item.
        $playlistItem = new \Google_Service_YouTube_PlaylistItem();
        $playlistItem->setSnippet($playlistItemSnippet);
        $playlistItemResponse = $youtube->playlistItems->insert(
            'snippet,contentDetails', $playlistItem, array());
    }
}