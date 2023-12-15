<?php
namespace Core\Tumblr_post\Models;
use CodeIgniter\Model;

class Tumblr_postModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/vendor/autoload.php');
        
        $this->consumer_key = get_option('tumblr_consumer_key', '');
        $this->consumer_secret = get_option('tumblr_consumer_secret', '');
        $this->tumblr = new \Tumblr\API\Client($this->consumer_key, $this->consumer_secret);
    }

    public function block_can_post(){
        return true;
    }

    public function block_plans(){
        return [
            "tab" => 10,
            "position" => 900,
            "permission" => true,
            "label" => __("Planning and Scheduling"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => sprintf("%s scheduling & report", $this->config['name']),
                ]
            ]
        ];
    }

    public function block_frame_posts($path = ""){
        return [
            "position" => 800,
        	"preview" => view( 'Core\Tumblr_post\Views\preview', [ 'config' => $this->config ] ),
            "advance_options" => view( 'Core\Tumblr_post\Views\advance_options', [ 'config' => $this->config ] )
        ];
    }

    public function post_handler($post){
        $data = json_decode($post->data, false);
        $medias = $data->medias;
        $shortlink_by = shortlink_by($data);

        try
        {

            $access_token = json_decode($post->account->token);
            $this->tumblr->setToken($access_token->oauth_token, $access_token->oauth_token_secret);
            $params = [];

            $caption = shortlink( spintax($data->caption), $shortlink_by );
            $link = shortlink( $data->link, $shortlink_by );

            $content = [];

            switch ($post->type)
            {
                case 'media':

                    if(is_image($medias[0]))
                    {
                        $images = array();
                        foreach ($medias as $key => $media){
                            $media = watermark($media, $post->account->team_id, $post->account->id);
                            $medias[$key] = $media;
                            if(is_image($media)){
                                $images[] = get_file_url($media);
                            }
                        }

                        $content = array(
                            "type" => "photo", 
                            "caption" => $caption,
                            "data" => $images
                        );
                    }

                    if(is_video($medias[0])){
                        $content = array(
                            "type" => "video", 
                            "caption" => $caption,
                            "data" => get_file_url($medias[0]),
                        );
                    }

                    break;

                case 'link':

                    $content = array(
                        "type" => "link", 
                        "url" => $link,
                        "description" => $caption
                    );

                    $link_info = get_link_info($link);

                    if($link_info['title'] != ""){
                        $content['title'] = $link_info['title'];
                    }

                    if($link_info['description'] != ""){
                        $content['excerpt'] = $link_info['description'];
                    }

                    if($link_info['image'] != ""){
                        $content['thumbnail'] = $link_info['image'];
                    }

                    break;

                case 'text':

                    $content = array(
                        "type" => "text", 
                        "title" => "",
                        "body" => $caption
                    );

                    break;
            }

            if( isset( $data->advance_options ) && isset( $data->advance_options->tumblr_tags ) && $data->advance_options->tumblr_tags != ""){
                $content['tags'] = $data->advance_options->tumblr_tags;
            }

            $response = $this->tumblr->createPost($post->account->pid, $content);
            unlink_watermark($medias);
            return [
                "status" => "success",
                "message" => __('Success'),
                "id" => $response->id,
                "url" => "https://".$post->account->username.".tumblr.com/post/".$response->id,
                "type" => $post->type
            ]; 

        } catch(\Exception $e) {
            unlink_watermark($medias);
            return array(
                "status"  => "error",
                "message" => $e->getMessage(),
                "type" => $post->type
            );
        }
    }
}
