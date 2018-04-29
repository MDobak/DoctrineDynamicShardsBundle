<?php

namespace MDobak\DoctrineDynamicShardsBundle\Shard;

/**
 * Class Configuration.
 *
 * @author Michał Dobaczewski
 */
class Configuration
{
	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var int
	 */
	protected $port;

	/**
	 * @var string
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $charset;

	/**
	 * Configuration constructor.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $host
	 * @param int    $port
	 * @param string $database
	 * @param string $charset
	 */
	public function __construct($username, $password, $host, $port, $database, $charset = 'UTF8')
	{
		$this->username = $username;
		$this->password = $password;
		$this->host     = $host;
		$this->port     = $port;
		$this->database = $database;
		$this->charset  = $charset;
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * @return int
	 */
	public function getPort(): int
	{
		return $this->port;
	}

	/**
	 * @return string
	 */
	public function getDatabase(): string
	{
		return $this->database;
	}

	/**
	 * @return string
	 */
	public function getCharset(): string
	{
		return $this->charset;
	}
}
