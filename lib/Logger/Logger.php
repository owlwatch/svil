<?php
namespace Svil\Logger;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class Logger extends AbstractLogger implements LoggerInterface
{
	/**
	 * Generic logging function
	 *
	 * @param string $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	public function log( $level, $message, array $context = array() )
	{
		// this simply logs to error_log for now...
		$str = "[{$level}] $message";
		if( !empty( $context ) ){
			$str.= ': '.print_r($context,1);
		}
		error_log( $str );
	}
}