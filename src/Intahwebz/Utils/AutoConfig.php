<?php


define('CONFIG_PATH', PATH_TO_ROOT."conf/yaml/");


trait AutoConfig{

	abstract public function parseConfig($configData);

	public function loadConfig(){

		$configData = NULL;
		$configFilename = $this->getConfigFilename();

		if(file_exists($configFilename) == TRUE){

			$yaml = file_get_contents($configFilename);
			$configData = yaml_parse($yaml);
		}

		$this->parseConfig($configData);
	}


	public function saveConfig($configData){

		$configFilename = $this->getConfigFilename();

		$yaml = yaml_emit($configData , YAML_UTF8_ENCODING, YAML_CRLN_BREAK);

		$result = file_put_contents($configFilename, $yaml);

		if($result === FALSE){
			throw new UnsupportedOperationException("Failed to write config file [$configFilename].");
		}

		echo "Should have saved file $configFilename";
		exit(0);
	}


	function getConfigFilename(){
		$className  = get_class();

		$firstChar = substr($className, 0, 1);

		if($firstChar == "\\"){
			$className = substr($className, 1);
		}

		$className = str_replace("\\", "/", $className);
		$className = str_replace(".", "", $className);

		return CONFIG_PATH.$className.".yaml";
	}
}

