<?php
namespace Core\Threads_post\Models;
use CodeIgniter\Model;

class Threads_postModel extends Model
{
    public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , '../Threads_profiles/Libraries/Instagram_threads_unofficial.php');
    }

    public function block_can_post(){
        return true;
    }

    public function block_plans(){
        return [
            "tab" => 10,
            "position" => 200,
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
            "position" => 200,
            "preview" => view( 'Core\Threads_post\Views\preview', [ 'config' => $this->config ] ),
        ];
    }

    public function post_validator($post){
        $errors = array();
        $data = json_decode( $post->data , 1);
        $medias = $data['medias'];

        if($post->social_network == 'threads'){
            if ($post->type == "media") {
                if( !empty($medias) && !is_image($medias[0]) ){
                    $errors[] = __("Currently, Sytemt not support video to Instagram Threads");
                }
            }
        }

        return $errors;
    }

    public function post_handler($post){
        if($post->api_type == 2){
            return $this->post_unofficial($post);
        }
    }

    public function post_unofficial($post){
        $data = json_decode($post->data, false);
        $medias = $data->medias;
        $post_type = "media";
        $shortlink_by = shortlink_by($data);

        if( $post->account->token == "" ){
            db_update(TB_ACCOUNTS, [ "status" => 0 ], [ "id" => $post->account->id ] );
            return [
                "status" => "error",
                "message" => __( "You have not authorized your Instagram account yet. Please re-login and try again" ),
                "type" => $post->type
            ];
        }

        $accessToken = json_decode($post->account->token);

        if( !is_array($accessToken) && (!isset($accessToken->ig_username) || !isset($accessToken->ig_password) || !isset($accessToken->token) || !isset($accessToken->user_id)) ){
            db_update(TB_ACCOUNTS, [ "status" => 0 ], [ "id" => $post->account->id ] );
            return [
                "status" => "error",
                "message" => __( "You have not authorized your Instagram account yet. Please re-login and try again" ),
                "type" => $post->type
            ];
        }

        $ig_username = $accessToken->ig_username;
        $ig_password = encrypt_decode($accessToken->ig_password);
        $token = $accessToken->token;
        $user_id = $accessToken->user_id;

        $proxy = get_proxy($post->account->proxy);
        $ig_auth = new \Instagram_threads_unofficial($ig_username, $ig_password, $post->team_id, $proxy);
        $response = $ig_auth->setAuth( $user_id, $token );

        try
        {
            $caption = shortlink( spintax($data->caption), $shortlink_by );
            $link = shortlink( $data->link, $shortlink_by );
            switch ($post->type) {
                case 'media':
                    $medias[0] = watermark($medias[0], $post->account->team_id, $post->account->id);
                    $response = $ig_auth->publish($caption, "", get_file_path($medias[0]));
                    break;

                case 'link':
                    $response = $ig_auth->publish($caption, $link, "");
                    break;
                
                default:
                    $response = $ig_auth->publish($caption, "", "");
                    break;
            }

            if($response['status'] == 'error'){
                return [
                    "status" => "error",
                    "message" => $response['message'],
                    "type" => $post->type
                ]; 
            }

            if($response['status'] == 'ok'){
                return [
                    "status" => "success",
                    "message" => __('Success'),
                    "id" => $response['media']["id"],
                    "url" => "https://www.threads.net/go/post/".$response['media']['code'],
                    "type" => $post->type
                ]; 
            }
        }
        catch (\Exception $e)
        {
            unlink_watermark($medias);
            return [
                "status" => "error",
                "message" => __( $e->getMessage() ),
                "type" => $post->type
            ];
        }
    }
}
