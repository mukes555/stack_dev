<?php
namespace Core\Analytics\Models;
use CodeIgniter\Model;

class AnalyticsModel extends Model
{
	public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
    }

    public function block_permissions($path = ""){
    	$items = get_blocks( "block_analytics", false );
        return [
        	"items" => $items,
        	"html" => view( 'Core\Analytics\Views\permissions', [ 'config' => $this->config, 'permissions' => $items ] )
        ];
    }

    public function analytics(){
        $module_paths = get_module_paths();
        $analytic_data = array();
        if(!empty($module_paths))
        {
            if( !empty($module_paths) ){
                foreach ($module_paths as $key => $module_path) {
                    $model_paths = $module_path . "/Models/";
                    $model_files = glob( $model_paths . '*' );


                    if ( !empty( $model_files ) )
                    {
                        foreach ( $model_files as $model_file )
                        {
                            $model_content = get_all_functions($model_file);
                            if ( in_array("block_analytics", $model_content) )
                                {   
                                $config_path = $module_path . "/Config.php";
                                $config_item = include $config_path;
                                include_once $model_file;
                                
                                $class = str_replace(COREPATH, "\\", $model_file);
                                $class = str_replace(".php", "", $class);
                                $class = str_replace("/", "\\", $class);
                                $class = ucfirst($class);
                                
                                $data = new $class;
                                $name = explode("\\", $class);
                                if(permission( $config_item['id'] ))
                                    $analytic_data[ strtolower( $config_item['parent']['id'] ) ] = $data->block_analytics();
                            }
                        }
                    }
                }
            }
        }

        if( !empty($analytic_data)){
            uasort($analytic_data, function($a, $b) {
                return $a['position'] <=> $b['position'];
            });

            return $analytic_data;
        }else{
            return false;
        }
    }
}
