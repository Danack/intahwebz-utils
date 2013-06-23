<?php

namespace Intahwebz\Utils;


trait Singleton{

	private static $instance = null;

	/**
	 * @return static
	 */
	public static function getInstance(){
		if(static::$instance == null){
			$newInstance = new self();
//			$newInstance->initInstance($data);
			static::$instance = $newInstance;
		}

		return static::$instance;
	}

	function initInstance(){
	}
};






?>