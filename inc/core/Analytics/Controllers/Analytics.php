<?php
namespace Core\Analytics\Controllers;

class Analytics extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
        $this->model = new \Core\Analytics\Models\AnalyticsModel();
    }
    
    public function index( $page = false ) {
        $analytic_data = $this->model->analytics();

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "analytics" => $analytic_data,
            "content" => view('Core\Analytics\Views\empty')
        ];

        if( isset($analytic_data[$page]) && isset($analytic_data[$page]['content']) ){
            $data['content'] = $analytic_data[$page]['content'];
        }


        return view('Core\Analytics\Views\index', $data);
    }
}