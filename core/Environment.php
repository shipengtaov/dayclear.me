<?php

class Environment{
	public static $option;

	/**
	 * ['file' => 'environment config file', 'key' => 'environment key']
	 */
	public static function setEnvironmentOption($option){
		self::$option['file'] = $option['file'];
		if (isset($option['key']))
			self::$option['key'] = $option['key'];
		else
			self::$option['key'] = 'environment';
	}

	public static function getEnvironment(){
		// null means production
		$environment = null;
		$hostname = gethostname();

		$environment_config = include self::$option['file'];
		if (isset($environment_config[self::$option['key']])){
			foreach ($environment_config[self::$option['key']] as $local_config_dir => $match_hostname) {
				if ($hostname == $match_hostname)
					return $local_config_dir;
			}
		}

		return $environment;
	}
}
