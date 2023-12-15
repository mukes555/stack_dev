<?php
/**
 * Class makes requests to Tinder API
 *
 * @author Alexey Suvorov
 */
class TinderAPI 
{
    
    const URL = 'https://api.gotinder.com/';
   
    protected static $routes = [
        'auth' => 'v2/auth/login/facebook',
        'getProfiles' => 'v2/recs/core',
        'like' => 'like/',
        'profile' => 'profile',
    ];

    /**
     * Auth in constructor
     * @param string $id facebook id
     * @param string $token facebook token
     */
    public function __construct($token = "") 
    {
        $this->header = $this->makeHeader($token); 
        if($token != ""){
            $authToken = $this->FBAuth($token);
            $this->setAuthtoken($authToken);
        }
    }

    /**
     * Auth in Tinder via Facebook
     * @param string $id 
     * @param string $token
     * @return string api token
     * @throws Exception
     */
    public function userinfo()
    {
        $response = $this->call(self::$routes['profile'], []);
        return $response;
    }

    /**
     * Auth in Tinder via Facebook
     * @param string $id 
     * @param string $token
     * @return string api token
     * @throws Exception
     */
    public function FBAuth($token)
    {
        $response = $this->call(self::$routes['profile'], [
			'token' => $token
		]);

        if(!$response) {
            return false;
        }

        if(isset($response['full_name'])){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * Get profiles
     * @return array
     * @throws Exception
     */
    public function getProfiles()
    {
        $response = $this->call(self::$routes['getProfiles']);
        if(!$response) {
            throw new Exception('Can\'t get the profiles');
        }
        
        return $response['data']['results'];
    }    
    
    /**
     * Like user
     * @param string $user_id
     * @return bool match?
     */
    public function like($user_id)
    {
        $response = $this->call(self::$routes['like'] . $user_id);
        return $response;
    }
    
    /**
     * Generate HTTP headers string from self::$headers
     * @return string
     */
    protected function makeHeader($token)
    {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            'Accept' => 'application/json',
            'X-Auth-Token' => $token
        ];

        $header = '';
        foreach($headers as $k => $v) {
            $header .= $k . ": " . $v . "\r\n";
        }

        return $header;
    }

    /**
     * Add auth token line into headers
     * @param string $authToken
     */
    public function setAuthtoken($authToken)
    {
        $this->header .= "X-Auth-Token: " . $authToken . "\r\n";
    }
    
    /**
     * Make request to API
     * @param string $path
     * @param string $data to POST
     * @return string result
     */
    protected function call($path, $data = [])
    {
        $context = [
			'http' => [
				'method' => (sizeof($data) === 0) ? 'GET' : 'POST',
				'header' => $this->header
			]
		];

        if(sizeof($data) > 0) {
            $context['http']['content'] = json_encode($data);
        }
        
        $url = self::URL . $path;
        $json = @file_get_contents($url, false, stream_context_create($context));

        $response = json_decode($json, true);

        return $response;
    }
}
