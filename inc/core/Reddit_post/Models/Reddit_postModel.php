<?php
namespace Core\Reddit_post\Models;
use CodeIgniter\Model;

class Reddit_postModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/redditoauth.php');
        $client_id = get_option("reddit_client_id", "");
        $client_secret = get_option("reddit_client_secret", "");
        $callback_url = get_module_url();

        $this->reddit = new \redditoauth($client_id, $client_secret, $callback_url);
    }

    public function block_can_post(){
        return true;
    }

    public function block_plans(){
        return [
            "tab" => 10,
            "position" => 800,
            "permission" => true,
            "label" => __("Planning and Scheduling"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => sprintf(__("%s scheduling & report"), $this->config['name']),
                ]
            ]
        ];
    }

    public function block_frame_posts($path = ""){
        return [
            "position" => 700,
        	"preview" => view( 'Core\Reddit_post\Views\preview', [ 'config' => $this->config ] ),
            "advance_options" => view( 'Core\Reddit_post\Views\advance_options', [ 'config' => $this->config ] )
        ];
    }

    public function post_validator($post){
        $errors = array();
        $data = json_decode( $post->data , 1);
        $medias = $data['medias'];

        if($post->social_network == 'reddit'){

            if( !isset( $data['advance_options'] ) || !isset( $data['advance_options']['reddit_title'] ) ||  $data['advance_options']['reddit_title'] == ""){
                $errors[] = __("A title for the post on Reddit is mandatory");
            }

            switch ($post->type) {
                case 'media':
                    if(empty($data['medias'])){
                        $errors[] = __("Reddit just support posting as image");
                    }else{
                        if(!is_image($medias[0]))
                        {
                            $errors[] = __("Reddit just support posting as image");
                        }
                    }
                    break;
            }
        }

        return $errors;
    }

    public function post_handler($post){
        $data = json_decode($post->data, false);
        $medias = $data->medias;
        $shortlink_by = shortlink_by($data);

        try
        {

            $access_token = $this->reddit->renew_access_token($post->account->token);
            
            $caption = shortlink( spintax($data->caption), $shortlink_by );
            $link = shortlink( $data->link, $shortlink_by );

            if(is_array($access_token)){
                $this->model->update($this->tb_account_manager, [ "status" => 0 ], [ "id" => $post->account->id ] );


                return [
                    "status" => "error",
                    "message" => $access_token['message'],
                    "type" => $post->type
                ];
            }



            $this->reddit->setAccessToken($access_token);

            if( isset( $data->advance_options ) && isset( $data->advance_options->reddit_title ) && $data->advance_options->reddit_title != ""){
                $title = shortlink( spintax($data->advance_options->reddit_title), $shortlink_by);
                $description = $caption;
            }else{
                $title = $caption;
                $description = "";
            }

            //$this->reddit->upload( $post->account->pid, get_file_path( $medias[0] ) );

            switch ($post->type)
            {
                case 'media':
                    $medias[0] = watermark($medias[0], $post->account->team_id, $post->account->id);
                    $response = $this->reddit->createStory($title, get_file_url($medias[0]), $post->account->pid, $description, "image");
                    break;

                case 'link':
                    $response = $this->reddit->createStory($title, $link, $post->account->pid, $description, "link");
                    break;

                case 'text':
                    $response = $this->reddit->createStory($title, null, $post->account->pid, $description, "self");
                    break;
            }

            if($response->success == 1){
                $url = "";
                switch ($post->type) {
                    case 'link':
                        $url = substr($response->jquery[10][3][0], 0, -1);
                        break;

                    case 'text':
                        $url = substr($response->jquery[10][3][0], 0, -1);
                        break;
                    
                    case 'media':
                        $url = substr($response->jquery[10][3][0], 0, -1);
                        break;
                }

                $post_id = explode("/comments/", $url);
                if(count($post_id) == 2){
                    $post_id = str_replace("/new_post", "", end($post_id));
                }else{
                    $post_id = "";
                }

                return [
                    "status" => "success",
                    "message" => __('Success'),
                    "id" => $post_id,
                    "url" => $url,
                    "type" => $post->type
                ]; 

            }else{

                if(isset($response->jquery[14][3][0])){
                    return array(
                        "status" => "error",
                        "message" => __( ucfirst($response->jquery[14][3][0]) ),
                        "type" => $post->type
                    );
                }

                if(isset($response->jquery[22][3][0])){
                    return array(
                        "status" => "error",
                        "message" => __( ucfirst( $response->jquery[22][3][0] ) ),
                        "type" => $post->type
                    );
                }
            }
        } catch(\Exception $e) {
            return [
                "status" => "error",
                "message" => __( $e->getMessage() ),
                "type" => $post->type
            ];
        }
    }
}
