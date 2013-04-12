<?php


class Profiler {

	static $instance = null;

	/**
	 * @var null|Timer
	 */
	var $timer = null;


	function __construct(){
		Profiler::$instance = $this;

		$this->timer = new Timer();

		$GLOBALS['shutdownFunctions'][] = 'profileFinalize';
	}

	function	start($profilePoint){
		$this->timer->startTime($profilePoint);
	}

	function	end($profilePoint){
		$this->timer->stopTime($profilePoint);
	}

	static function	finalize(){

		$instance = Profiler::getInstance();

		$string = $instance->timer->toString();

		if(FALSE){
			echo $string;
		}
	}

    function toString(){
        $instance = Profiler::getInstance();

        $string = $instance->timer->toString();

        return $string;
    }

	/**
	 * @static
	 * @return Profiler
	 */
	static function getInstance(){

		if(Profiler::$instance == null){
			$newProfiler = new Profiler();
		}

		return Profiler::$instance;
	}
}


$GLOBALS['profiler'] = new Profiler();

function    profileToString(){
    return $GLOBALS['profiler']->toString();
}

function	profileStart($profilePoint){
	$GLOBALS['profiler']->start($profilePoint);
}

function	profileEnd($profilePoint){

	$GLOBALS['profiler']->end($profilePoint);
}

function	profileFinalize(){

	$panel = getVariable("panel", FALSE);

	if($panel == TRUE){
		Profiler::finalize();
	}
}

?>