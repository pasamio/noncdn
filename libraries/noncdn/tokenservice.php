<?php

namespace NonCDN;

class TokenService
{
	private $configuration = null;
	private $options = null;

	public function __construct($configuration, $options = Array())
	{
		$this->configuration = $configuration;
		$defaults = Array('check_token_age'=>true);
		$this->options = (object)array_merge($defaults, $options);
	}

	public function convertTimestampToTime($timestamp=null)
	{
		if($timestamp == null)
		{
			$timestamp = time();
		}
		return gmdate('ymdH', $timestamp);
	}
		
	public function generateToken($user, $edge, $time = null)
	{
		if($time == null)
		{
			$time = $this->convertTimestampToTime();
		}
		$time = base_convert($time, 10, 36);
		$edgeSecret = $this->configuration->getEdgeSecret($edge);
		$token = md5($user.$edgeSecret.$time);
		return $edge.':'.$time.':MD5:'.$token;
	}

	public function getMaxTokenTime()
	{
		return gmdate('ymdH', time() - ($this->configuration->getMaxTokenAge() * 60 * 60));
	}

	public function validateToken($username, $token)
	{	
		$parts = explode(':', $token);
		if(count($parts) != 4)
		{
			\JLog::add('INVALID_NUMBER_OF_PARTS');
			return false;
		}
		$time = base_convert($parts[1], 36, 10);
		$verificationToken = $this->generateToken($username, $parts[0], $time);
		if($verificationToken != $token)
		{
			\JLog::add('TOKEN_MISMATCH');
			return false;
		}
		if($this->options->check_token_age && $time < $this->getMaxTokenTime())
		{
			\JLog::add('STALE_TOKEN');
			return false;
		}
		return true; 
	}

}
