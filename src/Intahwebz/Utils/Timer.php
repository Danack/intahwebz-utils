<?php


namespace Intahwebz\Utils;




//if(class_exists('Timer') == TRUE){
//	return;
//}


//static private $functions = array('startTime', 'test');
//	public function __call($method, $arguments){
//		// Note: value of $name is case sensitive.
//		//echo "Calling object method '$method' ". implode(', ', $arguments). "\n";
//
//		if (in_array($method, self::$functions)) {
//			array_unshift($arguments, $this);
//			return call_user_func_array('static::'.$method.'Instance', $arguments);
//		}
//	}
//
//	/**  As of PHP 5.3.0  */
//	public static function __callStatic($method, $arguments){
//
//		//echo "Calling static method '$name' "			. implode(', ', $arguments). "\n";
//
//		if (in_array($method, self::$functions)) {
//			array_unshift($arguments, self);
//			return call_user_func_array('static::'.$method.'Static', $arguments);
//		}
//	}



class Timer{

	use Singleton;

	private $arrayTimes = array();

	static function	start($point){
		$instance = self::getInstance();
		$instance->startTime($point);
	}


	function startTime($point){

		$this->arrayTimes[$point]['start'] = microtime();

		if(isset($this->arrayTimes[$point]['sum']) == FALSE){
			$this->arrayTimes[$point]['sum'] = 0;
		}
		
		if(isset($this->arrayTimes[$point]['count']) == FALSE){
			$this->arrayTimes[$point]['count'] = 1;
		}
		else{
			$this->arrayTimes[$point]['count']++;
		}
	}

	function stopTime($point, $comment = ''){

		$this->arrayTimes[$point]['end'] = microtime();
		$this->arrayTimes[$point]['comment'] = $comment;

		if(isset($this->arrayTimes[$point]['start']) == FALSE){
			logToFileFatal("Error in timer - point [$point] was not started, so cannot stop it");
			return;
		}

		$newTime  = microtime_diff( $this->arrayTimes[$point]['start'], $this->arrayTimes[$point]['end'] );

		if($newTime > 0){
			if($newTime < 0.0001){
				$newTime= 0.0001;
			}
		}
		else if($newTime < 0){
			if($newTime > -0.0001){
				$newTime = -0.0001;
			}
		}

		$this->arrayTimes[$point]['sum'] += $newTime;
	}

	function	getTime($point){

		if(isset($this->arrayTimes[$point]) == FALSE){
			return 0;
		}

		return	$this->arrayTimes[$point]['sum'];;
	}


	function getTimes($minimumTime = -1){

		$timesToShow = array();

        foreach($this->arrayTimes as $point => $values){

            $showTime = FALSE;

            if($minimumTime <= 0){
                $showTime = TRUE;
            }
            else if($minimumTime < $values['sum']){
                $showTime = TRUE;
            }

            if(	$showTime == TRUE){

                $timesToShow[$point] = array();

                $timesToShow[$point]['sum'] = $values['sum'];
                $timesToShow[$point]['count'] = $values['count'];

                //$string .= "$point: Sum = ".$values['sum'];

                if($this->arrayTimes[$point]['count'] > 1){
                    //$string .= " Average: ".($values['sum'] / $this->arrayTimes[$point]['count']);
                    $timesToShow[$point]['average'] = ($values['sum'] / $this->arrayTimes[$point]['count']);
                }

                //$string .= '<br/>';
            }
        }


		return $timesToShow;
	}

	function toString($minimumTime = -1){

        $string = "";

		$timesToShow = $this->getTimes($minimumTime);

        foreach($timesToShow as $point => $details){

            $string .= "$point: Sum = ".$details['sum'];

            if(array_key_exists('average', $details) == TRUE){
                $string .= " Average: ".$details['average'];
            }

            $string .= "<br/>";
        }

		return $string;
	}


}
