<?php 

class AppModel extends Model {
	function find($conditions = null, $fields = array(), $order = null, $recursive = null) {
		//if (Configure::read('Cache.disable') === false && Configure::read('Cache.check') === true && isset($fields['cache']) && $fields['cache'] !== false) {
		  if (isset($fields['cache']) && $fields['cache'] !== false) {
			$key = $fields['cache'];
			$expires = '+1 month';
			if (is_array($fields['cache'])) {
				$key = $fields['cache'][0];
				
				if (isset($fields['cache'][1])) {
					$expires = $fields['cache'][1];
				}
			}
	
			// Set cache settings
			Cache::config('sql_cache', array(
				'prefix' 	=> strtolower($this->name) .'-',
				'duration'	=> $expires
			));
			
			// Load from cache
			$results = Cache::read($key, 'sql_cache');
			
			if (!is_array($results)) { 
				$results = parent::find($conditions, $fields, $order, $recursive);
				Cache::write($key, $results, 'sql_cache');
			}
			
			return $results;
		}
		
		// Not cacheing
		return parent::find($conditions, $fields, $order, $recursive);
	}
	
	
	function nativeQuery($query,$Cache= false,$QryKey=null,$QryExpires=null){
	
		if(isset($query) && !empty($query) && is_string($query)){
		
			if($Cache != false && isset($QryKey) && !is_null($QryKey)){
			
				$key = $QryKey;
				$expires = '+1 month';
			
				if (isset($QryExpires) && !is_null($QryExpires) ) {
					$expires=$QryExpires;
				}
			
				// cache settings
				Cache::config('sql_cache', array(
				'prefix'     => strtolower($this->name) .'-',
				'duration'    => $expires
				));
			
				// read result from cache
				$results = Cache::read($key, 'sql_cache');
				
				if (!is_array($results)) {
					$results = $this->query($query);
					Cache::write($key, $results, 'sql_cache');
				}
			
				return $results;
			
			}else{
				//NON-CHACHED QUERY
				$result=$this->query($query);
				return $result;
			}
		
		}else{
			// no query available
			return false;
		}
	
	}
}

?>