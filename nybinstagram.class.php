<?php

class NybInstagram extends Instagram {
	public $aSettings = array(
		'wp-options' => array(               // database stored valeus
			'nybinstagram_client_id'			=> null,		// app id
			'nybinstagram_client_secret'		=> null,		// app secret
			'nybinstagram_access_token'			=> null,		// oAuth access token
			'nybinstagram_account_id'			=> null,		// oAuth account ID
			'nybinstagram_account_name'			=> null,		// oAuth account name
			'nybinstagram_hashtag'				=> null,		// a given hashtag to follow
			'nybinstagram_follow_account'		=> null,		// toggle follow of oAuth account
			'nybinstagram_follow_hashtag'		=> null,		// toggle follow of hashtag
			'nybinstagram_logincode'			=> null,	// outbound api check code
			'nybinstagram_photo_object'			=> null,	// stored api photo object
		),
		'api-settings' => array(
			'callback_url'       => null,
			'refresh-interval'   => 60, //set to seconds
		),
		'logincode-prefix' 	=> 'logincode_',
		'wp-table'			=> 'nybinstagram',
		'cache'				=> array(
			'limit'				=> 12,
		),
		'bDebug'			=> false,
	);

	public $bDebug	= true;
	public $aErrors = array();
	public $aMessages = array();

	public function __construct($aArgs, $aSettings){
		/**
		*  Called when object is created
		*
		**/
		$this->aSettings['api-settings']['callback_url'] = $this->get_redirect_url();

		foreach ($aSettings['wp-options'] as $sKey => $sValue) {
			$this->aSettings['wp-options'][$sKey] = $sValue; //retains original unset settings
		}

		$aArgs['apiCallback'] = $this->aSettings['api-settings']['callback_url'];
		
		parent::__construct($aArgs); 
		$this->aSettings['wp-options']['nybinstagram_access_token'] = get_option('nybinstagram_access_token');
		$this->check_instagram_auth();

	}

	public function log($xMessage){
		if($this->bDebug == true) {
			if(is_array($xMessage) || is_object($xMessage)){
				if(is_array($xMessage)){
					$sPrefix = 'Object: ';
				} else {
					$sPrefix = 'Array: ';
				}
				error_log($sPrefix . json_encode($xMessage));
			} else {
				error_log($xMessage);
			}
			return true;
		}
		return false;
	}

	public function get_settings_page(){

		if($this->aSettings['wp-options']['nybinstagram_client_id']==null || $this->aSettings['wp-options']['nybinstagram_client_secret']==null){
				require('templates/settings/instagram_essential_settings.php');
		} else {
			//die(json_encode($this->aSettings['wp-options']));
			if($this->aSettings['wp-options']['nybinstagram_access_token']=='null' || $this->aSettings['wp-options']['nybinstagram_access_token']==null){
				// Require instagram auth to gain access token
				require('templates/settings/instagram_login_button.php');
			} else {
				require('templates/settings/instagram_general_settings.php');
			}
		}
	}
	
	public function check_instagram_auth(){
		if(isset($_GET['code']) && $_GET['code']!==''){

			$oAccess = $this->getOAuthToken($_GET['code']);
			$aAccess = json_decode(json_encode($oAccess));

			if($oAccess->access_token != '' || $oAccess->access_token!=null) {
				update_option('nybinstagram_access_token', $oAccess->access_token, null, 1);
			}
			update_option('nybinstagram_account_name', $oAccess->user->username, null, 1);
			update_option('nybinstagram_account_id', $oAccess->user->id, null, 1);

			$sAccess = $oAccess->access_token;
			$this->aSettings['wp-options']['nybinstagram_access_token'] == $sAccess; 	// update just in case
			$this->setAccessToken($aAccess); // set object access token just in case

			$sURL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$aURLPart = explode('&code', $sURL);
			$sURL =  $aURLPart[0];// . '&auth=success';
			echo '<script type="text/javascript">
						window.location = "' . $sURL . '"
					</script>';
		} elseif(isset($_GET['auth'])){
			$this->aMessages[] = 'Instagram authenticated successfully.';
		}
	}

	public function form_handler($aArgs){
		foreach ($aArgs as $sKey => $sValue) {
			if(array_key_exists($sKey, $this->aSettings['wp-options']) && $sValue!='') {
				update_option($sKey, $sValue, null, 1);
				$this->aSettings['wp-option'][$sKey] = $sValue;
				$bUpdated = true;
			} else {
				//error_log('key does not exist: ' . $sKey . ', ' . $sValue);
			}
		}
		if($bUpdated){
			// Truncate and rebuild all data so no outdated account/tags are left over
			global $wpdb;
			$oDelete1 = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . $this->aSettings['wp-table']);				
		}
	}

	public function create_options(){
		/**
		*  Create all given options (called on plug-in activate)
		*
		**/

		$aSettings = $this->aSettings;

		foreach ($aSettings['wp-options'] as $sKey => $sValue) {
			if($sValue == null) { // on plugin active: create all settings unless they have a value already
				update_option($sKey, 'null', null, 1);
			}
		}      
	}

	public function update_options($aOptions){
		/**
		*  Update stored options with values
		*
		**/
	}

	public function reset_options($sOption = null){
		/**
		*  Reset follow flags & tokens (called on plug-in deactivate)
		*
		**/

		if($sOption !== null){
			update_option($sOption, 'null', null, 1);
		} else {
			update_option('nybinstagram_client_id', 'null', null, 1);
			update_option('nybinstagram_client_secret', 'null', null, 1);
			update_option('nybinstagram_access_token', 'null', null, 1);

			update_option('nybinstagram_follow_hashtag', 'false', null, 1);

			update_option('nybinstagram_account_name', 'null', null, 1);
			update_option('nybinstagram_follow_account', 'false', null, 1);
			update_option('nybinstagram_follow_hashtag', 'false', null, 1);
		}
	}

	public function check_login_code(){
		$sCode = $this->aSettings['logincode-prefix'];
		$bReturn = false;

		foreach ($_GET as $sKey => $sValue) {
			if(substr($sKey, 0,strlen($sCode)) == $sCode) {
				error_log("sKeyfound: " . $sKey . ', sKeyCode: ' . substr($sKey, strlen($sCode)));
				if($aSettings['wp_options']['nybinstagram_logincode']==substr($sKey, strlen($sKey))){
					$bReturn = true;
				}
			}
		}
		return $bReturn;
	}

	private function get_redirect_url(){
		return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/wp-admin/options-general.php?page=" . $_GET['page'];
	}

	public function get_frontend_template(){
		$this->set_data();
		require 'templates/frontend/recursive_blocks.php';
	}

	public function set_data(){
		global $wpdb;
		$this->data = new stdClass();
		$this->data->account = new stdClass();
		$this->data->hashtag = new stdClass();
		$this->data->all 	 = new stdClass();
		// ACCOUNT
		$this->data->account = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . ' WHERE sourcetype=2 order by likes desc, comments desc, created desc limit ' . $this->aSettings['cache']['limit']);
		// HASHTAG
		$this->data->hashtag = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . ' WHERE sourcetype=1 order by likes desc, comments desc, created desc limit ' . $this->aSettings['cache']['limit']);
		// ALL
		$this->data->all = $wpdb->get_results('select * from ' . $wpdb->prefix . $this->aSettings['wp-table'] . ' WHERE id IN( select id from ( select id from ' . $wpdb->prefix . $this->aSettings['wp-table'] . ' where sourcetype=1 limit ' . (int) ($this->aSettings['cache']['limit']/2) . ')a) OR id IN ( select id from ( select id from ' . $wpdb->prefix . $this->aSettings['wp-table'] . ' where sourcetype=2 limit ' . (int) ($this->aSettings['cache']['limit']/2) . ')b) order by likes desc, comments desc, created desc');

		if($this->aSettings['wp-options']['nybinstagram_follow_hashtag'] == 'true' && $this->aSettings['wp-options']['nybinstagram_follow_account'] == 'true'){
			$this->data->selected = $this->data->all;
		} elseif($this->aSettings['wp-options']['nybinstagram_follow_hashtag'] == 'true' && $this->aSettings['wp-options']['nybinstagram_follow_account'] != 'true' ) {
			$this->data->selected = $this->data->hashtag;
		} else {
			$this->data->selected = $this->data->account;
		}
	}

	public function create_database() {
			
			error_log("Initialising database...");
			global $wpdb;
			$table_name = $wpdb->prefix . $this->aSettings['wp-table']; 


			$sql 	= 	"CREATE TABLE $table_name (
						id int unsigned NOT NULL AUTO_INCREMENT,
						user_id int,
						image_id VARCHAR(32),
						thumbnail VARCHAR(255),
						low_resolution VARCHAR(255),
						standard_resolution VARCHAR(255),
						link VARCHAR(255),
						created datetime,
						likes smallint unsigned,
						comments smallint unsigned,
						caption TEXT,
						username VARCHAR(64),
						fullname VARCHAR(128),
						profile_pic VARCHAR(255),
						sourcetype smallint unsigned,
						record_created datetime,
						record_updated timestamp NOT NULL default now() on update now(),
						UNIQUE KEY id (id),
						UNIQUE INDEX image_id (image_id(32)) 
						);";		   

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
	}

	public function rebuild_data(){
		/**
		*	1) Save latest 20 of each required, on duplicate ignore
		*	2) Find count of followed, delete excess by created date (oldest first)
		*
		**/


		$this->log("Rebuilding data...");
		$this->log("HASHTAG: " . $this->aSettings['wp-options']['nybinstagram_hashtag']);
		$this->log("HASHTAG: " . get_option('nybinstagram_hashtag'));
		$this->log("ACCOUNT: " . $this->aSettings['wp-options']['nybinstagram_follow_account']);
		$this->log("ACCOUNT: " . get_option('nybinstagram_follow_account'));
		global $wpdb;
		$table_name = $wpdb->prefix . "nybinstagram"; 

		$oToken = new stdClass();
		$oToken->access_token = $this->aSettings['wp-options']['nybinstagram_access_token'];
		$this->setAccessToken($oToken);

		$iSaved = 0;
		$iDownloaded = 0;
		$iLimit = (int) ($this->aSettings['cache']['limit']);

		if(get_option('nybinstagram_follow_hashtag') == 'true') {
		 	$oInstPhotos = $this->getTagMedia(get_option('nybinstagram_hashtag'), 20 );
		 	if(!isset($oInstPhotos->data)) {
				$this->aErrors[] = 'No hashtag photos found. Try re-authenticating.';
		 	} else {
				foreach ($oInstPhotos->data as $oInstPhoto) {
					$sQuery0 = 'INSERT INTO ' . $table_name . ' VALUES ( ' .
								 'null,' . 
								 mysql_escape_string($oInstPhoto->user->id) . ',' . 
								 '"' . mysql_escape_string($oInstPhoto->id) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->thumbnail->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->low_resolution->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->standard_resolution->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->link) . '",' . 
								 '"' . mysql_escape_string(date("Y-m-d H:i:s", $oInstPhoto->created_time)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->likes->count) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->comments->count) . '",' . 
								 '"' . mysql_escape_string(preg_replace('/[^(\x20-\x7F)]*/','', $oInstPhoto->caption->text)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->user->username) . '",' . 
								 '"' . mysql_escape_string(preg_replace('/[^(\x20-\x7F)]*/','', $oInstPhoto->user->full_name)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->user->profile_picture) . '",' . 
								 '"' . mysql_escape_string("1") . '",' . 
								 '"' . date("Y-m-d H:i:s", time()) . '",' . 
								 '"' . date("Y-m-d H:i:s", time()) . '"' .
							') on duplicate key update likes=' . mysql_escape_string($oInstPhoto->likes->count) . ', comments=' . mysql_escape_string($oInstPhoto->comments->count) . '';

					if($wpdb->query($sQuery0) == 1){
						$iSaved++;
					}      
					$iDownloaded++;
				}
		 	}
			$this->log("Downloaded $iDownloaded photos...(hashtag)");
			$this->log("Saved $iSaved photos...(hashtag)");
		}
			
		if(get_option('nybinstagram_follow_account') == 'true') {

			$oInstPhotos = $this->getUserMedia('self', 20);
		 	if(!isset($oInstPhotos->data)) {
				$this->aErrors[] = 'No photos found. Try re-authenticating.';
		 	} else {
				foreach ($oInstPhotos->data as $oInstPhoto) {
					$sQuery0 = 'INSERT INTO ' . $table_name . ' VALUES ( ' .
								 'null,' . 
								 mysql_escape_string($oInstPhoto->user->id) . ',' . 
								 '"' . mysql_escape_string($oInstPhoto->id) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->thumbnail->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->low_resolution->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->images->standard_resolution->url) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->link) . '",' . 
								 '"' . mysql_escape_string(date("Y-m-d H:i:s", $oInstPhoto->created_time)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->likes->count) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->comments->count) . '",' . 
								 '"' . mysql_escape_string(preg_replace('/[^(\x20-\x7F)]*/','', $oInstPhoto->caption->text)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->user->username) . '",' . 
								 '"' . mysql_escape_string(preg_replace('/[^(\x20-\x7F)]*/','', $oInstPhoto->user->full_name)) . '",' . 
								 '"' . mysql_escape_string($oInstPhoto->user->profile_picture) . '",' . 
								 '"' . "2" . '",' . 
								 '"' . date("Y-m-d H:i:s", time()) . '",' . 
								 '"' . date("Y-m-d H:i:s", time()) . '"' .
							') on duplicate key update likes=' . mysql_escape_string($oInstPhoto->likes->count) . ', comments=' . mysql_escape_string($oInstPhoto->comments->count) . '';

					if($wpdb->query($sQuery0) == 1){
						$iSaved++;
					}      
				}
		 	}			
			$this->log("Saved $iSaved photos...(account)");
		}	
			
		$this->log("==========================================");
		$this->log("Completed. Saved $iSaved / $iLimit photos.");


		/**
		*	Delete exccess rows (only store capped limit)
		*
		**/

		$oDelete1 = $wpdb->get_results("DELETE  FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . " WHERE id IN ( SELECT id FROM ( SELECT id FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . " WHERE sourcetype=1 order by created desc LIMIT " . $this->aSettings['cache']['limit'] . ",500) a);"); //account				
		$oDelete2 = $wpdb->get_results("DELETE  FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . " WHERE id IN ( SELECT id FROM ( SELECT id FROM " . $wpdb->prefix . $this->aSettings['wp-table'] . " WHERE sourcetype=2 order by created desc LIMIT " . $this->aSettings['cache']['limit'] . ",500) a);"); //account				
		$this->set_data();
	}

}

?>