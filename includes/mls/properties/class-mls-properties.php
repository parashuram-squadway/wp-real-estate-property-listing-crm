<?php
namespace mls;
/**
 * Handle logic for fetching properties
 * */
class Properties{

	protected static $instance = null;

	private $account_details;

	public $search_data;

	public function __construct(){
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function setPropertySearchData($search_data){
		$this->search_data = $search_data;
	}

	private function getPropertySearchData(){
		return $this->search_data;
	}

	private function get_default_location(){
		$zip 		= 0;
		$account 	= \crm\AccountEntity::get_instance()->get_account_details();
		if( isset($account->zipcode) ){
			$zip = $account->zipcode;
		}
		return $zip;
	}

	/**
	 * get properties
	 *
	 * @return array | object
	 * */
	public function get_properties($search_data = null){
		$communityid = '';
		if( sanitize_text_field(isset($search_data['communityid'])) ){
			$communityid = sanitize_text_field($search_data['communityid']);
		}elseif( sanitize_text_field(isset($_REQUEST['communityid'])) ){
			$communityid = sanitize_text_field($_REQUEST['communityid']);
		}

		$countryid = '';
		if( sanitize_text_field(isset($search_data['countryid'])) ){
			$countryid = sanitize_text_field($search_data['countryid']);
		}elseif( sanitize_text_field(isset($_REQUEST['countryid'])) ){
			$countryid = sanitize_text_field($_REQUEST['countryid']);
		}

		$countyid = '';
		if( sanitize_text_field(isset($search_data['countyid'])) ){
			$countyid = sanitize_text_field($search_data['countyid']);
		}elseif( sanitize_text_field(isset($_REQUEST['countyid'])) ){
			$countyid = sanitize_text_field($_REQUEST['countyid']);
		}

		$stateid = '';
		if( sanitize_text_field(isset($search_data['stateid'])) ){
			$stateid = sanitize_text_field($search_data['stateid']);
		}elseif( sanitize_text_field(isset($_REQUEST['stateid'])) ){
			$stateid = sanitize_text_field($_REQUEST['stateid']);
		}

		$cityid = '';
		if( sanitize_text_field(isset($search_data['cityid'])) ){
			$cityid = sanitize_text_field($search_data['cityid']);
		}elseif( sanitize_text_field(isset($_REQUEST['cityid'])) ){
			$cityid = sanitize_text_field($_REQUEST['cityid']);
		}

		$zip = '';
		if( sanitize_text_field(isset($search_data['zip'])) ){
			$zip = sanitize_text_field($search_data['zip']);
		}elseif( sanitize_text_field(isset($_REQUEST['zip'])) ){
			$zip = sanitize_text_field($_REQUEST['zip']);
		}

		$lat = '';
		if( sanitize_text_field(isset($search_data['lat'])) ){
			$lat = sanitize_text_field($search_data['lat']);
		}elseif( sanitize_text_field(isset($_REQUEST['lat'])) ){
			$lat = sanitize_text_field($_REQUEST['lat']);
		}

		$lon = '';
		if( sanitize_text_field(isset($search_data['lon'])) ){
			$lon = sanitize_text_field($search_data['lon']);
		}elseif( sanitize_text_field(isset($_REQUEST['lon'])) ){
			$lon = sanitize_text_field($_REQUEST['lon']);
		}

		$q = $this->get_default_location();
		if( sanitize_text_field(isset($search_data['location'])) && sanitize_text_field($search_data['location']) != '' ){
			$q = sanitize_text_field($search_data['location']);
		}elseif( sanitize_text_field(isset($_REQUEST['location'])) && sanitize_text_field($_REQUEST['location']) != '' ){
			$q = sanitize_text_field($_REQUEST['location']);
		}

		$bathrooms = '';
		if( sanitize_text_field(isset($search_data['bathrooms'])) ){
			$bathrooms = sanitize_text_field($search_data['bathrooms']);
		}elseif( sanitize_text_field(isset($_REQUEST['bathrooms'])) ){
			$bathrooms = sanitize_text_field($_REQUEST['bathrooms']);
		}

		$bedrooms = '';
		if( sanitize_text_field(isset($search_data['bedrooms'])) ){
			$bedrooms = sanitize_text_field($search_data['bedrooms']);
		}elseif( sanitize_text_field(isset($_REQUEST['bedrooms'])) ){
			$bedrooms = sanitize_text_field($_REQUEST['bedrooms']);
		}

		$min_listprice = '0';
		if( sanitize_text_field(isset($search_data['min_listprice'])) ){
			$min_listprice = sanitize_text_field($search_data['min_listprice']);
		}elseif( sanitize_text_field(isset($_REQUEST['min_listprice'])) ){
			$min_listprice = sanitize_text_field($_REQUEST['min_listprice']);
		}

		$max_listprice = '0';
		if( sanitize_text_field(isset($search_data['max_listprice'])) ){
			$max_listprice = sanitize_text_field($search_data['max_listprice']);
		}elseif( sanitize_text_field(isset($_REQUEST['max_listprice'])) ){
			$max_listprice = sanitize_text_field($_REQUEST['max_listprice']);
		}

		$property_status = 'Active';
		if( sanitize_text_field(isset($search_data['status'])) ){
			$property_status = sanitize_text_field($search_data['status']);
		}elseif( sanitize_text_field(isset($_REQUEST['status'])) ){
			$property_status = sanitize_text_field($_REQUEST['status']);
		}

		$property_type = '0';
		if( sanitize_text_field(isset($search_data['type'])) ){
			$property_type = sanitize_text_field($search_data['type']);
		}elseif( sanitize_text_field(isset($_REQUEST['type'])) ){
			$property_type = sanitize_text_field($_REQUEST['type']);
		}

		$limit = '11';
		if( sanitize_text_field(isset($search_data['limit'])) ){
			$limit = sanitize_text_field($search_data['limit']);
		}elseif( sanitize_text_field(isset($_REQUEST['limit'])) ){
			$limit = sanitize_text_field($_REQUEST['limit']);
		}

		$paged = 1;
		if( isset($_REQUEST['paged']) ){
			$paged = $_REQUEST['paged'];
		}elseif( get_query_var( 'page' ) ){
			$page = get_query_var( 'page' ) ? absint( get_query_var( 'page' ) ):$paged;
		}

		$transaction = '';
		if(
			sanitize_text_field(isset($search_data['transaction'])) &&
			sanitize_text_field($search_data['transaction']) != '' &&
			sanitize_text_field($search_data['transaction']) != 'all'
		){
			$ex_string = explode(' ',$search_data['transaction']);
			if( isset($ex_string[1]) ){
				$transaction = $ex_string[1];
			}else{
				$transaction = $search_data['transaction'];
			}
		}elseif(
			sanitize_text_field(isset($_REQUEST['transaction'])) &&
			sanitize_text_field($_REQUEST['transaction']) != '' &&
			sanitize_text_field($_REQUEST['transaction']) != 'all'
		){
			$ex_string = explode(' ',$_REQUEST['transaction']);
			$transaction = $ex_string[1];
		}elseif(
			sanitize_text_field($search_data['transaction']) == 'all' ||
			sanitize_text_field($_REQUEST['transaction']) == 'all'
		){
			$transaction = 'Sale';
		}

		$data = array(
			'communityid'	=> $communityid,
			'countryid'		=> $countryid,
			'countyid'		=> $countyid,
			'stateid'		=> $stateid,
			'cityid'		=> $cityid,
			'lat' 			=> $lat,
			'lon' 			=> $lon,
			'q'				=> $q,
			'bathrooms' 	=> $bathrooms,
			'bedrooms' 		=> $bedrooms,
			'min_listprice' => $min_listprice,
			'max_listprice' => $max_listprice,
			'status'		=> $property_status,
			'type'			=> $property_type,
			'transaction'	=> $transaction,
			'limit'			=> $limit,
			'page'			=> $paged
		);
		//var_dump($data);
		$search_md5 	  = md5(json_encode($data));
		$property_keyword = \Property_Cache::get_instance()->getCacheSearchKeyword();
		$cache_keyword 	  = $property_keyword->id . '-mls-' . $search_md5;
		// save the cache keyword as it is md5
		//\DB_Store::get_instance()->del($cache_keyword);
		if( \DB_Store::get_instance()->get($cache_keyword) ){
			$get_properties = \DB_Store::get_instance()->get($cache_keyword);
		}else{
			$md_client 	= \Clients\Masterdigm_MLS::instance()->connect();
			$properties = $md_client->getProperties( $data );

			if( isset($properties->result) == 'success' )
			{
				foreach( $properties->properties as $property ){

					$p =	new \mls\Property_Entity;
					$p->bind( $property );

					$data_properties[] = $p;
				}
				$total = 0;
				$obj_data_properties = array();
				if( isset($data_properties) && $data_properties ){
					$total 					= count($data_properties);
					$obj_data_properties 	= $data_properties;
				}

				$get_properties = (object)array(
					'total'			=>$total,
					'data'			=>$obj_data_properties,
					'search_keyword'=>$data,
					'source'		=>'mls'
				);
				\DB_Store::get_instance()->put($cache_keyword, $get_properties);
			}else{
				$properties_count = 0;
				if( isset($properties->count) ){
					$properties_count = $properties->count;
				}
				$properties_msg = '';
				if( isset($properties->messsage) ){
					$properties_msg = $properties->messsage;
				}
				$properties_request = '';
				if( isset($properties->request) ){
					$properties_request = $properties->request;
				}
				$get_properties = (object)array(
					'total'			=>$properties_count,
					'result'		=>$properties_count,
					'messsage'		=>$properties_msg,
					'request'		=>$properties_request,
					'search_keyword'=>array(),
					'source'		=>'mls'
				);
			}
		}

		return $get_properties;
	}


	public function getNextPrevData(){
		return false;
	}

	public function get_property_by_id($matrix_unique_id){
		$data = array(
			'properties'=>array(),
			'photos'	=>array(),
			'result'	=>'fail'
		);
		$cache_keyword = 'mls_single_'.$matrix_unique_id;
		//\DB_Store::get_instance()->del($cache_keyword);
		if( \DB_Store::get_instance()->get($cache_keyword) ){
			return \DB_Store::get_instance()->get($cache_keyword);
		}else{
			$md_client 		= \Clients\Masterdigm_MLS::instance()->connect();
			$property 		= $md_client->getPropertyByMatrixID( $matrix_unique_id );

			if( $property ){
				$photos = array();
				$propertyEntity = new \mls\Property_Entity;
				$propertyEntity->bind( $property->property );
				$photos	  		= $md_client->getPhotosByMatrixID( $matrix_unique_id );
				if( isset($photos->photos) ){
					$photos = $photos->photos;
				}
				$data = array(
					'properties'=>$propertyEntity,
					'photos'	=>$photos,
					'result'	=> 'success',
					'source'=>'mls'
				);
				\DB_Store::get_instance()->put($cache_keyword, $data);
				return $data;
			}else{
				return false;
			}
		}
		return false;
	}

	public function getRelatedProperties($matrix_unique_id){
		$md_client 			= \Clients\Masterdigm_MLS::instance()->connect();
		$relatedProperties 	= $md_client->getRelatedPropertiesByMatrixID( $matrix_unique_id );
		$property_array = array();
		//var_dump($relatedProperties);
		if( $relatedProperties['result'] != 'fail' ){
			foreach( $relatedProperties->properties as $property ){

				$p =	new \mls\Property_Entity;
				$property_array[] = $p->bind( $property );
			}
		}else{
			$property_array['result'] = $relatedProperties['result'];
		}
		return $property_array;
	}

	public function get_broker_id(){
		return get_option( 'broker_id' );
	}
}