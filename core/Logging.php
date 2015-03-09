<?php

class Logging{
	public static $levels=[
		'NOTSET' => 0,
		'DEBUG' => 10,
		'INFO' => 20,
		'WARNING' => 30,
		'ERROR' => 40,
		'CRITICAL' => 50
	];
	public static $level="DEBUG";
	public static $file;
	public static $format;
	public static $datefmt="Y-m-d H:i:s";

	public static function basicConfig($level, $options=[]){
		if (!isset(self::$levels[$level]))
			throw new Exception("no level named {$level}");
		else
			self::$level = $level;
		if (isset($options['file']))
			self::$file = $options['file'];
		if (isset($options['format']))
			self::$format = $options['format'];
		if (isset($options['datefmt']))
			self::$datefmt = $options['datefmt'];
	}

	public static function debug($message){
		if (!self::shouldLog('DEBUG'))
			return;
		$log_message = self::makeMessage('DEBUG', $message);
		self::doLog($log_message);
	}

	public static function info($message){
		if (!self::shouldLog('INFO'))
			return;
		$log_message = self::makeMessage('INFO', $message);
		self::doLog($log_message);
	}

	public static function warning($message){
		if (!self::shouldLog('WARNING'))
			return;
		$log_message = self::makeMessage('WARNING', $message);
		self::doLog($log_message);
	}

	public static function warn($message){
		self::warning($message);
	}

	public static function error($message){
		if (!self::shouldLog('ERROR'))
			return;
		$log_message = self::makeMessage('ERROR', $message);
		self::doLog($log_message);
	}

	public static function critical($message){
		if (!self::shouldLog('CRITICAL'))
			return;
		$log_message = self::makeMessage('CRITICAL', $message);
		self::doLog($log_message);
	}

	/**
	 * @return true | false
	 */
	public static function shouldLog($level_name){
		if (self::$levels[$level_name] >= self::$levels[self::$level])
			return true;
		else
			return false;
	}

	/**
	 * todo: format
	 */
	public static function makeMessage($levelname, $message){
		$message = date(self::$datefmt) . ':' . $levelname . ' ' . $message;
		return $message;
	}

	public static function doLog($message, $new_line=true){
		if (isset(self::$file)){
			$fp = fopen(self::$file, 'a');
			fwrite($fp, $message);
			if ($new_line)
				fwrite($fp, "\n");
			fclose($fp);
		} else {
			echo $message;
			if ($new_line){
				if (PHP_SAPI == 'cli')
					echo PHP_EOL;
				else
					echo "<br>";
			}
		}
	}
}
