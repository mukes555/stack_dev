<?php
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Psr7\MultipartStream;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\RSA;
if(!class_exists("Instagram_threads_unofficial")){

include get_module_dir( __DIR__ , 'Libraries/vendor/autoload.php');
class Instagram_threads_unofficial
{
	const DEVICE_STRING    = '26/8.0.0; 640dpi; 1440x2560; samsung; SM-G935F; hero2lte; samsungexynos8890';
	const LATEST_ANDROID_APP_VERSION = '291.0.0.31.111';
	const DEFAULT_LSD_TOKEN = 'NjppQDEgONsU_1LCzrmp6q';
	const BASE_API_URL = 'https://i.instagram.com';
	const LOGIN_URL = self::BASE_API_URL . '/api/v1/bloks/apps/com.bloks.www.bloks.caa.login.async.send_login_request/';
	const POST_URL = self::BASE_API_URL . '/api/v1/media/configure_text_only_post/';
	const POST_WITH_IMAGE_URL = self::BASE_API_URL . '/api/v1/media/configure_text_post_app_feed/';
	const LOGIN_EXPERIMENTS =
	  'ig_android_fci_onboarding_friend_search,ig_android_device_detection_info_upload,ig_android_account_linking_upsell_universe,ig_android_direct_main_tab_universe_v2,ig_android_allow_account_switch_once_media_upload_finish_universe,ig_android_sign_in_help_only_one_account_family_universe,ig_android_sms_retriever_backtest_universe,ig_android_direct_add_direct_to_android_native_photo_share_sheet,ig_android_spatial_account_switch_universe,ig_growth_android_profile_pic_prefill_with_fb_pic_2,ig_account_identity_logged_out_signals_global_holdout_universe,ig_android_prefill_main_account_username_on_login_screen_universe,ig_android_login_identifier_fuzzy_match,ig_android_mas_remove_close_friends_entrypoint,ig_android_shared_email_reg_universe,ig_android_video_render_codec_low_memory_gc,ig_android_custom_transitions_universe,ig_android_push_fcm,multiple_account_recovery_universe,ig_android_show_login_info_reminder_universe,ig_android_email_fuzzy_matching_universe,ig_android_one_tap_aymh_redesign_universe,ig_android_direct_send_like_from_notification,ig_android_suma_landing_page,ig_android_prefetch_debug_dialog,ig_android_smartlock_hints_universe,ig_android_black_out,ig_activation_global_discretionary_sms_holdout,ig_android_video_ffmpegutil_pts_fix,ig_android_multi_tap_login_new,ig_save_smartlock_universe,ig_android_caption_typeahead_fix_on_o_universe,ig_android_enable_keyboardlistener_redesign,ig_android_sign_in_password_visibility_universe,ig_android_nux_add_email_device,ig_android_direct_remove_view_mode_stickiness_universe,ig_android_hide_contacts_list_in_nux,ig_android_new_users_one_tap_holdout_universe,ig_android_ingestion_video_support_hevc_decoding,ig_android_mas_notification_badging_universe,ig_android_secondary_account_in_main_reg_flow_universe,ig_android_secondary_account_creation_universe,ig_android_account_recovery_auto_login,ig_android_pwd_encrytpion,ig_android_bottom_sheet_keyboard_leaks,ig_android_sim_info_upload,ig_android_mobile_http_flow_device_universe,ig_android_hide_fb_button_when_not_installed_universe,ig_android_account_linking_on_concurrent_user_session_infra_universe,ig_android_targeted_one_tap_upsell_universe,ig_android_gmail_oauth_in_reg,ig_android_account_linking_flow_shorten_universe,ig_android_vc_interop_use_test_igid_universe,ig_android_notification_unpack_universe,ig_android_registration_confirmation_code_universe,ig_android_device_based_country_verification,ig_android_log_suggested_users_cache_on_error,ig_android_reg_modularization_universe,ig_android_device_verification_separate_endpoint,ig_android_universe_noticiation_channels,ig_android_account_linking_universe,ig_android_hsite_prefill_new_carrier,ig_android_one_login_toast_universe,ig_android_retry_create_account_universe,ig_android_family_apps_user_values_provider_universe,ig_android_reg_nux_headers_cleanup_universe,ig_android_mas_ui_polish_universe,ig_android_device_info_foreground_reporting,ig_android_shortcuts_2019,ig_android_device_verification_fb_signup,ig_android_onetaplogin_optimization,ig_android_passwordless_account_password_creation_universe,ig_android_black_out_toggle_universe,ig_video_debug_overlay,ig_android_ask_for_permissions_on_reg,ig_assisted_login_universe,ig_android_security_intent_switchoff,ig_android_device_info_job_based_reporting,ig_android_add_account_button_in_profile_mas_universe,ig_android_add_dialog_when_delinking_from_child_account_universe,ig_android_passwordless_auth,ig_radio_button_universe_2,ig_android_direct_main_tab_account_switch,ig_android_recovery_one_tap_holdout_universe,ig_android_modularized_dynamic_nux_universe,ig_android_fb_account_linking_sampling_freq_universe,ig_android_fix_sms_read_lollipop,ig_android_access_flow_prefil';
	const SIGNATURE_KEY = '9193488027538fd3450b83b7d05286d4ca9599a0f7eeed90d8c85925698a05dc';

	private $username;
	private $password;
	private $token;
	private $user_id;
	private $deviceParams      = [];
	private $settings          = [];
	private $need_to_save_data = FALSE;
	private $cache_data_id;
	private $tmpFiles 		   = [];
	private $lsdToken          = "fTlm_o9vMB0kgTW9asNt7q";
	/**
	 * @var Client
	 */
	private $client;
	/**
	 * @var bool|CookieJar
	 */
	private $cookies;
	/**
	 * @var bool
	 */
	private $mid = NULL;

	public function __construct ( $username = "", $password = "", $team_id = "", $proxy = "" )
	{
		$this->team_id = $team_id;
		$this->username = $username;
		$this->password = $password;

		$cookies = TRUE;

		$ig_account = db_get("*", TB_ACCOUNT_SESSIONS, ["social_network" => "threads", "username" => $username, "team_id" => $team_id]);

		if ( $ig_account )
		{
			$this->cache_data_id = $ig_account->id;

			$settings = json_decode( $ig_account->settings, TRUE );
			if ( ! empty( $settings ) && is_array( $settings ) )
			{
				$this->settings = $settings;
			}

			$cookies_arr = json_decode( $ig_account->cookies, TRUE );

			foreach ( $cookies_arr as $cook )
			{
				if ( $cook[ 'Name' ] == 'mid' )
				{
					$this->mid = $cook[ 'Value' ];
				}
			}

			$cookies = is_array( $cookies_arr ) ? new CookieJar( FALSE, $cookies_arr ) : TRUE;
		}

		$this->initDefaultSettings();

		if ( empty( $this->getSettings( 'advertising_id' ) ) )
		{
			$this->setSettings( 'advertising_id', $this->generateUUID() );
		}

		if ( empty( $this->getSettings( 'session_id' ) ) )
		{
			$this->setSettings( 'session_id', $this->generateUUID() );
		}

		$this->cookies = $cookies;
		$this->client  = new Client( [
			'proxy'       => empty( $proxy ) ? NULL : $proxy,
			'verify'      => FALSE,
			'http_errors' => FALSE,
			'headers'     => [
				"User-Agent" 				=> "Barcelona ".self::LATEST_ANDROID_APP_VERSION." Android",
				"Content-Type" 				=> "application/x-www-form-urlencoded; charset=UTF-8",
				"authority" 				=> "www.threads.net",
			    "accept" 					=> "*/*",
			    "accept-language" 			=> "en-US",
			    "cache-control"				=> "no-cache",
			    "origin" 					=> "https://www.threads.net",
			    "pragma" 					=> "no-cache",
			    "Sec-Fetch-Site" 			=> "same-origin",
			    "x-asbd-id" 				=> "129477",
			    "x-fb-lsd" 					=> "fTlm_o9vMB0kgTW9asNt7q",
			    "x-ig-app-id" 				=> "238260118697367",
			],
			'cookies'     => $cookies
		] );
	}

	public function __destruct ()
	{
		$this->emptyTmpFile($this->tmpFiles);

		if ( $this->need_to_save_data )
		{
			if ( ! is_null( $this->cache_data_id ) )
			{
				db_update(TB_ACCOUNT_SESSIONS, [
					'settings' => json_encode( $this->settings ),
					'cookies'  => json_encode( $this->getCookies() ),
					'last_modified' => time()
				],[
					'id' => $this->cache_data_id
				]);
			}
			else
			{
				db_insert(TB_ACCOUNT_SESSIONS, [
					'settings' => json_encode( $this->settings ),
					'cookies'  => json_encode( $this->getCookies() ),
					'team_id' => $this->team_id,
					'social_network' => 'threads',
					'username' => $this->username,
					'last_modified' => time()
				]);
			}
		}
	}

	private function buildHeaders ( $additionalHeaders = [] )
	{
		$user_info = $this->getUserInfo();
	
		$headers = [
			"User-Agent" 				=> "Barcelona ".self::LATEST_ANDROID_APP_VERSION." Android",
			"Content-Type" 				=> "application/x-www-form-urlencoded; charset=UTF-8",
			"authority" 				=> "www.threads.net",
		    "accept" 					=> "*/*",
		    "accept-language" 			=> "en-US",
		    "cache-control"				=> "no-cache",
		    "origin" 					=> "https://www.threads.net",
		    "pragma" 					=> "no-cache",
		    "Sec-Fetch-Site" 			=> "same-origin",
		    "x-asbd-id" 				=> "129477",
		    "x-fb-lsd" 					=> $user_info['lsdToken'],
		    "x-ig-app-id" 				=> "238260118697367",
		];

		return array_merge( $headers, $additionalHeaders );
	}

  	public function syncLoginExperiments (){
  		$uid = $this->generateUUID();
  		$sendData = [
			"id" => $uid,
			"experiments" => self::LOGIN_EXPERIMENTS,
		];

		try
		{
			$response = (string) $this->client->post( self::BASE_API_URL."/api/v1/qe/sync/", [
				'form_params' => $this->signData( $sendData ),
				'headers'     => $this->buildHeaders([
					'Sec-Fetch-Site' => 'same-origin',
					'X-DEVICE-ID' => $uid,
				])
			] )->getBody();

			$response = json_decode($response);
		}
		catch ( Exception $e )
		{
			throw new Exception( 'Sync login experiment failed' );
		}
  	}

	public function login ()
	{
		$this->syncLoginExperiments();

		$blockVersion = "5f56efad68e1edec7801f630b5c122704ec5378adbee6609a448f105f34a9c73";
		$bkClientContext = json_encode( [
			'bloks_version' => $blockVersion,
          	'styles_id' => 'instagram',
		] );

		$device_id = $this->getSettings( 'device_id' );

		$params = json_encode([
			"client_input_params" => [
				"password" => $this->encPass( $this->password ),
				"contact_point" => $this->username,
				"device_id" => $device_id,
				
			],
			"server_params" => [
				"credential_type" => "password",
				"device_id" => $device_id
			]
		]);

		$sendData = [
			"params" => $params,
			"bk_client_context" => $bkClientContext,
			"bloks_versioning_id" => $blockVersion,
		];

		try
		{
			$response = (string) $this->client->post( self::LOGIN_URL, [
				'form_params' => $this->signData( $sendData ),
				'headers'     => $this->buildHeaders()
			] )->getBody();

			$response = str_replace("\\","", $response);

			if( preg_match( '/Incorrect Password\"/i', $response ) ){
				throw new Exception( 'The password you entered is incorrect. Please try again.' );
			}

			if( preg_match( '/Please wait a few minutes before you try again.\"/i', $response ) ){
				throw new Exception( 'It seems that the system cannot log in to Instagram Threads with the current IP or proxy. Try using other proxies and try again.' );
			}

			preg_match( '/Bearer IGT:2\:([a-zA-Z0-9=]+?)\"/i', $response, $token );
			preg_match( '/pk_id\":\"([0-9=]+?)\"/i', $response, $user_id );

			if(empty($token) || empty($user_id)){
				throw new Exception( 'Login Failed' );
			}

			$this->setAuth($user_id[1], $token[1]);

			$response = [
				'status' 	=> 'ok',
				'token'  	=> $token[1],
				'username'	=> $this->username,
				'user_id'	=> $user_id[1],
			];
		}
		catch ( Exception $e )
		{
			throw new Exception( $e->getMessage() );
		}

		$this->need_to_save_data = TRUE;

		return $response;
	}

	public function setAuth( $user_id, $token ){
		$this->user_id = $user_id;
		$this->token = $token;
	}
	
	public function get_curl($url, $custom_headers = false){
        $user_agent='Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3';
        
        if($custom_headers){
            $headers = $custom_headers;
        }else{
            $headers = array
            (
                'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,fr;q=0.8;q=0.6,en;q=0.4,ar;q=0.2',
                'Accept-Encoding: gzip,deflate',
                'Accept-Charset: utf-8;q=0.7,*;q=0.7',
                'cookie:datr=; locale=en_US; sb=; pl=n; lu=gA; c_user=; xs=; act=; presence='
            ); 
        }

        $ch = curl_init( $url );

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , "GET");
        curl_setopt($ch, CURLOPT_POST, false);     
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_REFERER, base_url());

        $result = curl_exec( $ch );
       
        curl_close( $ch );

        return $result;
    }

	public function getUserInfo(){
    	$response = $this->get_curl( 'https://www.instagram.com/'.$this->username);
    	
    	preg_match( '/\"user_id\":\"([0-9]{5,15})\"/i', $response, $user_id );
    	preg_match( '/\"token\":\"([a-zA-Z0-9]{5,15})\"/i', $response, $lsdToken );

		if(!empty($user_id)){
			$user_id = $user_id[1];
		}else{
			$user_id = "";
		}

		if(!empty($lsdToken)){
			$this->lsdToken = $lsdToken[1];
		}

		return ['user_id' => $user_id, 'lsdToken' => $this->lsdToken];
	}

	public function getUserProfile(){
		try
		{
			$sendData = [
				"lsd" => "fTlm_o9vMB0kgTW9asNt7q",
		        "variables" => json_encode(["userID" => $this->user_id]),
		        "doc_id" => '23996318473300828',
			];

			$response = (string) $this->client->post( 'https://www.threads.net/api/graphql', [
				'form_params' =>  $sendData
			] )->getBody();
			$response = json_decode( $response, TRUE );


			if(!isset($response['data'])){
				$response = [];
			}

			$response = $response['data']['userData']['user'];
		}
		catch ( Exception $e )
		{
			$response = [];
		}

		return $response;
	}

	public function publish($caption = "", $link = "", $photo = ""){
		$endpoint = self::POST_URL;
		$device_id = $this->getSettings( 'device_id' );

		$app_info = ["reply_control" => 0];

		$data = [
        	"text_post_app_info" => $app_info,
    		"timezone_offset" => 0,
    		"source_type" => 4,
    		"_uid" => $this->user_id,
    		"device_id" => $device_id,
    		"caption" => $caption,
        	"upload_id" => $this->createUploadId(),
        	"device" => json_encode([
        		"manufacturer" => "OnePlus",
        		"model" => "ONEPLUS+A3003",
        		"android_version" => 26,
        		"android_release" => "8.1.0"
        	]),
        ];

		if($link != ""){
			$app_info['link_attachment_url'] = $link;
		}

		if($photo != ""){
			$endpoint = self::POST_WITH_IMAGE_URL;
			$upload = $this->uploadImage($photo);

			if(!isset($upload['upload_id'])){
				throw new Exception( ! empty( $upload[ 'message' ] ) && is_string( $upload[ 'message' ] ) ? strip_tags( $upload[ 'message' ] ) : 'Upload error!' );
			}

			$data['upload_id'] = $upload['upload_id'];
      		$data['scene_capture_type'] = '';
		}else{
			$data['publish_mode'] = "text_post";
		}

		$app_info = json_encode($app_info);
		$data["text_post_app_info"] = $app_info;

		$sendData = [
	        "signed_body" => "SIGNATURE.".json_encode($data)
		];

		$response = (string) $this->client->post( $endpoint, [
			'form_params' =>  $sendData,
			'headers' => $this->buildHeaders(
				["authorization" => "Bearer IGT:2:".$this->token]
			)
		] )->getBody();
		$response = json_decode( $response, TRUE );

		return $response;
	}

	private function uploadImage($photo){
		$photo_info = $this->validatePhoto($photo);

		$uploadId = $this->createUploadId();

		$params = [
			'media_type'          => '1',
			'upload_media_height' => (string) $photo_info[ 'height' ],
			'upload_media_width'  => (string) $photo_info[ 'width' ],
			'upload_id'           => $uploadId,
			'image_compression'   => '{"lib_name":"moz","lib_version":"3.1.m","quality":"87"}',
			'xsharing_user_ids'   => '[]',
			'retry_context'       => json_encode( [
				'num_step_auto_retry'   => 0,
				'num_reupload'          => 0,
				'num_step_manual_retry' => 0
			] ),
			'IG-FB-Xpost-entry-point-v2' => 'feed'
		];

		$entity_name = sprintf( '%s_%d_%d', $uploadId, 0, $this->hashCode( basename( $photo ) ) );
		$endpoint    = 'https://i.instagram.com/rupload_igphoto/' . $entity_name;

		try
		{
			$response = (string) $this->client->post( $endpoint, [
				'headers' => $this->buildHeaders([
					'authorization' 				=> 'Bearer IGT:2:'.$this->token,
					'X_FB_PHOTO_WATERFALL_ID'    	=> $this->generateUUID(),
					'X-Requested-With'           	=> 'XMLHttpRequest',
					'X-CSRFToken'                	=> $this->getCsrfToken(),
					'X-Instagram-Rupload-Params' 	=> json_encode( $this->reorderByHashCode( $params ) ),
					'X-Entity-Type'              	=> 'image/jpeg',
					'X-Entity-Name'              	=> $entity_name,
					'X-Entity-Length'            	=> filesize( $photo ),
					'Offset'                     	=> '0'
				]),
				'body'    => fopen( $photo, 'r' )
			] )->getBody();

			$response = json_decode( $response, TRUE );
		}
		catch ( Exception $e )
		{
			$response = [];
		}

		return $response;
	}

	private function emptyTmpFile(){
		$tmpFiles = $this->tmpFiles;
		if(!empty($tmpFiles)){
			foreach ($tmpFiles as $tmpFile) {
				if(file_exists($tmpFile)){
					unlink($tmpFile);
				}
			}
		}
	}

	
	private function getSettings ( $key )
	{
		return key_exists( $key, $this->settings ) ? $this->settings[ $key ] : NULL;
	}

	private function setSettings ( $key, $value )
	{
		$this->settings[ $key ] = $value;
	}

	private function setSettingsIfEmpty ( $key, $value )
	{
		if ( ! isset( $this->settings[ $key ] ) || empty( $this->settings[ $key ] ) )
		{
			$this->settings[ $key ] = $value;
		}
	}

	private function initDefaultSettings ()
	{
		$this->setSettingsIfEmpty( 'devicestring', static::DEVICE_STRING );
		$this->setSettingsIfEmpty( 'device_id', $this->generateDeviceId() );
		$this->setSettingsIfEmpty( 'phone_id', $this->generateUUID() );
		$this->setSettingsIfEmpty( 'uuid', $this->generateUUID() );
		$this->setSettingsIfEmpty( 'account_id', '' );
	}

	private function getCookies ()
	{
		$cookies_purified = [];
		$cookies          = $this->client->getConfig( 'cookies' )->toArray();

		foreach ( $cookies as $cookie )
		{
			if ( $cookie[ 'Name' ] == 'sessionid' && empty( trim( $cookie[ 'Value' ], '\"' ) ) )
			{
				continue;
			}

			$cookies_purified[] = $cookie;
		}

		return $cookies_purified;
	}

	private function getCookie ( $name, $default = '' )
	{
		$cookies = $this->getCookies();
		$value   = $default;

		foreach ( $cookies as $cookieInf )
		{
			if ( $cookieInf[ 'Name' ] == $name )
			{
				$value = $cookieInf[ 'Value' ];
			}
		}

		return $value;
	}

	private function getCsrfToken ()
	{
		return $this->getCookie( 'csrftoken' );
	}

	private function generateDeviceId ()
	{
		return 'android-' . substr( md5( number_format( microtime( TRUE ), 7, '', '' ) ), 16 );
	}

	private function generateUUID ( $keepDashes = TRUE )
	{
		$uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0x0fff ) | 0x4000, mt_rand( 0, 0x3fff ) | 0x8000, mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ) );

		return $keepDashes ? $uuid : str_replace( '-', '', $uuid );
	}

	private function getDeviceParam ( $param )
	{
		if ( ! isset( $this->deviceParams[ $param ] ) )
		{
			$this->buildUserAgent();
		}

		return isset( $this->deviceParams[ $param ] ) ? $this->deviceParams[ $param ] : '';
	}

	private function buildUserAgent ( $app_version = '107.0.0.27.121', $version_code = '168361634' )
	{
		$this->deviceParams[ 'appVersion' ]  = $app_version;  //'203.0.0.29.118' - '314665256';
		$this->deviceParams[ 'versionCode' ] = $version_code; //'314665256';
		$this->deviceParams[ 'userLocale' ]  = 'en_US';

		$deviceString = $this->getSettings( 'devicestring' );

		$parts = explode( '; ', $deviceString );

		$androidOS = explode( '/', $parts[ 0 ], 2 );

		$resolution                         = explode( 'x', $parts[ 2 ], 2 );
		$this->deviceParams[ 'pixelCount' ] = (int) $resolution[ 0 ] * (int) $resolution[ 1 ];

		$manufacturerAndBrand = explode( '/', $parts[ 3 ], 2 );

		$this->deviceParams[ 'androidVersion' ] = $androidOS[ 0 ];
		$this->deviceParams[ 'androidRelease' ] = $androidOS[ 1 ];
		$this->deviceParams[ 'dpi' ]            = $parts[ 1 ];
		$this->deviceParams[ 'resolution' ]     = $parts[ 2 ];
		$this->deviceParams[ 'manufacturer' ]   = $manufacturerAndBrand[ 0 ];
		$this->deviceParams[ 'brand' ]          = ( isset( $manufacturerAndBrand[ 1 ] ) ? $manufacturerAndBrand[ 1 ] : NULL );
		$this->deviceParams[ 'model' ]          = $parts[ 4 ];
		$this->deviceParams[ 'device' ]         = $parts[ 5 ];
		$this->deviceParams[ 'cpu' ]            = $parts[ 6 ];

		$this->deviceParams[ 'manufacturerWithBrand' ] = $this->deviceParams[ 'brand' ] !== NULL ? $this->deviceParams[ 'manufacturer' ] . '/' . $this->deviceParams[ 'brand' ] : $this->deviceParams[ 'manufacturer' ];

		return sprintf( 'Instagram %s Android (%s/%s; %s; %s; %s; %s; %s; %s; %s; %s)', $this->deviceParams[ 'appVersion' ], $this->deviceParams[ 'androidVersion' ], $this->deviceParams[ 'androidRelease' ], $this->deviceParams[ 'dpi' ], $this->deviceParams[ 'resolution' ], $this->deviceParams[ 'manufacturerWithBrand' ], $this->deviceParams[ 'model' ], $this->deviceParams[ 'device' ], $this->deviceParams[ 'cpu' ], $this->deviceParams[ 'userLocale' ], $this->deviceParams[ 'versionCode' ] );
	}

	private function signData ( $data, $exclude = [] )
	{
		$result = [];

		foreach ( $exclude as $key )
		{
			if ( isset( $data[ $key ] ) )
			{
				$result[ $key ] = $data[ $key ];
				unset( $data[ $key ] );
			}
		}

		foreach ( $data as &$value )
		{
			if ( is_scalar( $value ) )
			{
				$value = (string) $value;
			}
		}

		$data = json_encode( (object) $this->reorderByHashCode( $data ) );

		$result[ 'ig_sig_key_version' ] = 4;
		$result[ 'signed_body' ]        = $this->generateSignature( $data ) . '.' . $data;

		return $this->reorderByHashCode( $result );
	}

	private function encPass ( $password )
	{
		list( $publicKeyId, $publicKey ) = $this->getPublicKeys();
		$key  = substr( md5( uniqid( mt_rand() ) ), 0, 32 );
		$iv   = substr( md5( uniqid( mt_rand() ) ), 0, 12 );
		$time = time();

		$rsa          = PublicKeyLoader::loadPublicKey( base64_decode( $publicKey ) );
		$rsa          = $rsa->withPadding( RSA::ENCRYPTION_PKCS1 );
		$encryptedRSA = $rsa->encrypt( $key );

		$aes = new AES( 'gcm' );
		$aes->setNonce( $iv );
		$aes->setKey( $key );
		$aes->setAAD( strval( $time ) );
		$encrypted = $aes->encrypt( $password );

		$payload = base64_encode( "\x01" | pack( 'n', intval( $publicKeyId ) ) . $iv . pack( 's', strlen( $encryptedRSA ) ) . $encryptedRSA . $aes->getTag() . $encrypted );

		return sprintf( '#PWD_INSTAGRAM:4:%s:%s', $time, $payload );
	}

	private function validatePhoto ( $photo )
	{
        if (empty($photo) || !is_file($photo)) {
            throw new \InvalidArgumentException(sprintf('The photo file "%s" does not exist on disk.', $photo));
        }

        $filesize = filesize($photo);
        if ($filesize < 1) {
            throw new \InvalidArgumentException(sprintf(
                'The photo file "%s" is empty.',
                $photo
            ));
        }

        $result = @getimagesize($photo);
        if ($result === false) {
            throw new \InvalidArgumentException(sprintf('The photo file "%s" is not a valid image.', $photo));
        }
        list($width, $height, $type) = $result;

        return [
        	'width' => $width,
        	'height' => $height,
        	'type' => $type
        ];
	}

	private function generateSignature ( $data )
	{
		return hash_hmac( 'sha256', $data, 'c36436a942ea1dbb40d7f2d7d45280a620d991ce8c62fb4ce600f0a048c32c11' );
	}

	private function reorderByHashCode ( $data )
	{
		$hashCodes = [];
		foreach ( $data as $key => $value )
		{
			$hashCodes[ $key ] = $this->hashCode( $key );
		}

		uksort( $data, function ( $a, $b ) use ( $hashCodes ) {
			$a = $hashCodes[ $a ];
			$b = $hashCodes[ $b ];
			if ( $a < $b )
			{
				return -1;
			}
			else if ( $a > $b )
			{
				return 1;
			}
			else
			{
				return 0;
			}
		} );

		return $data;
	}

	private function hashCode ( $string )
	{
		$result = 0;
		for ( $i = 0, $len = strlen( $string ); $i < $len; ++$i )
		{
			$result = ( -$result + ( $result << 5 ) + ord( $string[ $i ] ) ) & 0xFFFFFFFF;
		}

		if ( PHP_INT_SIZE > 4 )
		{
			if ( $result > 0x7FFFFFFF )
			{
				$result -= 0x100000000;
			}
			else if ( $result < -0x80000000 )
			{
				$result += 0x100000000;
			}
		}

		return $result;
	}

	private function createUploadId ()
	{
		return number_format( round( microtime( TRUE ) * 1000 ), 0, '', '' );
	}

	private function getPublicKeys ()
	{
		$client = new Client();

		$response = '';

		try
		{
			$response = $client->get( "https://i.instagram.com/api/v1/qe/sync/" );
		}
		catch ( Exception $e )
		{
			if ( method_exists( $e, 'getResponse' ) )
			{
				$response = $e->getResponse();

				if ( is_null( $response ) )
				{
					return false;
				}
			}
		}

		if ( ! method_exists( $response, 'getHeader' ) )
		{
			return false;
		}

		if ( empty( $response->getHeader( "ig-set-password-encryption-key-id" )[ 0 ] ) || empty( $response->getHeader( "ig-set-password-encryption-pub-key" )[ 0 ] ) )
		{
			return false;
		}

		$pubKeyID  = $response->getHeader( "ig-set-password-encryption-key-id" )[ 0 ];
		$pubKeyStr = $response->getHeader( "ig-set-password-encryption-pub-key" )[ 0 ];

		return [
			$pubKeyID,
			$pubKeyStr,
		];
	}

	
}

}