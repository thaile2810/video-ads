<?php
namespace Zendvn\Api;
/**
 * Project : YoutbeDownloader
 * User: AidenDam
 * Using code from https://github.com/jeckman/YouTube-Downloader
 */
class YoutbeDownloader{
    private static $endpoint = "http://www.youtube.com/get_video_info";

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function getLink($id) {
        $API_URL = self::$endpoint . "?&video_id=" . $id;
        $video_info = $this->curlGet($API_URL);

        $url_encoded_fmt_stream_map = '';
        parse_str($video_info,$output);
        if(isset($reason)) {
            return $reason;
        }
        echo '<pre>';
             print_r($output);
        echo '</pre>';
        if (isset($url_encoded_fmt_stream_map)) {
            $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
            echo '<pre>';
                 print_r($my_formats_array);
            echo '</pre>';
        } else {
            return 'No encoded format stream found.';
        }
        if (count($my_formats_array) == 0) {
            return 'No format stream map found - was the video id correct?';
        }
        $avail_formats[] = '';
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = $type = $url = '';
        $expire = time();
        foreach ($my_formats_array as $format) {
            parse_str($format);
            $avail_formats[$i]['itag'] = $itag;
            $avail_formats[$i]['quality'] = $quality;
            $type = explode(';', $type);
            $avail_formats[$i]['type'] = $type[0];
            $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
            parse_str(urldecode($url));
            $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
            $avail_formats[$i]['ipbits'] = $ipbits;
            $avail_formats[$i]['ip'] = $ip;
            $i++;
        }
        return $avail_formats;
    }

    public function curlGet($URL) {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }
} 