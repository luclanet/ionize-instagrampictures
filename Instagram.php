<?php
/**
 * Ionize Instagram Pictures
 *
 * Import and show pictures from instagram
 *
 * @package		Ionize-InstagramPictures
 * @subpackage	Libraries
 * @category	TagManager Libraries
 * @author    Claudio Mulas - www.luclanet.it
 *
class TagManager_Instagram extends TagManager
{
    public static $tag_definitions = array
    (
        "instagram" =>      "instagram",
        "instagram:media" => "tag_media"
    );

    public function __construct()
    {
        // If the CI object is needed :
    }
    public static function index(FTL_Binding $tag)
    {

    }

    public static function instagram(FTL_Binding $tag)
    {
        // Returned string
        $str = '';

        /* You can find it here
         * https://apigee.com/console/instagram
         *
         * Select from "Authentication" dropdown Oauth2 and copy access_token from a demo request
         */

        $cache = true;
        $age_cache = 3 * 60; // 3 hours

        // Access token
        $access_token = $tag->getAttribute('access_token');

        $url = "https://api.instagram.com/v1/users/self/media/recent?access_token=".$access_token;
        $cachefile = "./cache/instagram_".md5($url);

        if ($cache && file_exists($cachefile))
            $data = file_get_contents($cachefile);
        else {
            $data = file_get_contents($url);
            if ($cache) file_put_contents($cachefile,$data);
        }

        // Cache data?


        $data = json_decode($data,true);

        /* Delete cache */
        if($cache && is_file($cachefile) && time() - filemtime($cachefile) >= $age_cache)
            unlink($cachefile);

        $limit = $tag->getAttribute("limit");
        $range = $tag->getAttribute("range");
        if ( ! is_null($limit) )
            $data['data'] = array_slice($data['data'],0,$limit);
        elseif ( ! is_null($range) )
        {
            list($from,$to) = explode(",",$range);
            $data['data'] = array_slice($data['data'],$from,$from+$to);
        }

        foreach ($data['data'] as $d) {
            $tag->set('media', $d);
            $str .= $tag->expand();
        }

        return $str;
    }

    public static function tag_media(FTL_Binding $tag)
    {
        $media = $tag->get('media');

        $size = $tag->getAttribute("size");

        if ( ! is_null($size) ) {
            $media = $tag->get('media');
            $fullmedia = array();
            $tmp = pathinfo($media['images']['standard_resolution']['url']);
            $fullmedia['extension'] = $tmp['extension'];

            if ( ! file_exists("./files/.instagram/")) mkdir("./files/.instagram/");


            $fullmedia['file_name'] = md5($media['images']['standard_resolution']['url']).".".$fullmedia['extension'];

            $fullmedia['path'] = "files/.instagram/".$fullmedia['file_name'];

            if ( ! file_exists($fullmedia['path']) ) copy($media['images']['standard_resolution']['url'],$fullmedia['path']);



            $fullmedia['base_path'] = "files/.instagram/";

            $fullmedia['mime'] = mime_content_type($fullmedia['path']);

            $settings = self::_get_src_settings($tag);

            $src = self::$ci->medias->get_src($fullmedia, $settings, Settings::get('no_source_picture'));

            return self::output_value($tag , $src);
        }
    }

    protected static function _get_src_settings(FTL_Binding $tag)
    {
        $setting_keys = array
        (
            'method',		// 'square', 'adaptive', 'border', 'width', 'height'
            'size',
            'watermark',
            'unsharp',
            'start',		// attribute for 'square' method
            'color',		// attribute for 'border' method
            'refresh',
        );

        $settings = array_fill_keys($setting_keys, '');

        // <ion:media /> parent
        $parent = $tag->getParent('media');
        if ( !is_null($parent))
            $settings = array_merge($settings, $parent->getAttributes());

        $settings = array_merge($settings, $tag->getAttributes());

        if (empty($settings['method']))
            $settings['method'] = self::$default_resize_method;

        return $settings;
    }


    public static $default_resize_method = 'fill';
}

