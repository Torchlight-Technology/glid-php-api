<?php

namespace torchlighttechnology\glid;

use torchlighttechnology\glid\APIError;

/**
 * TTG Global ID PHP client
 *
 * @author guyandy
 */

class GlobalIdAPI
{
	protected $api_host;

	public function __construct($api_host)
	{
		$this->api_host = $api_host;
	}

	/**
	 * Lookup Email
	 *
	 * @return array API response object.
	 */
	public function email_lookup($email)
	{
		$request = array(
			'request' => 'email_lookup',
			'email' => $email
		);
		return $this->api_request($request);
	}

	/**
	 * Lookup Phone by Email
	 *
	 * @return array API response object.
	 */
	public function phone_lookup_by_email($email)
	{
		$request = array(
			'request' => 'phone_lookup',
			'email' => $email
		);
		return $this->api_request($request);
	}

	/**
	 * Lookup Hash
	 *
	 * @return array API response object.
	 */
	public function hash_lookup($phone)
	{
		$request = array(
			'request' => 'hash_lookup',
			'phone' => $phone
		);
		return $this->api_request($request);
	}

	/**
	 * Last Action
	 *
	 * @return array API response object.
	 */
	public function last_action($phone)
	{
		$request = array(
			'request' => 'last_action',
			'phone' => $phone
		);
		return $this->api_request($request);
	}

	/**
	 * History
	 *
	 * @return array API response object.
	 */
	public function history($phone)
	{
		$request = array(
			'request' => 'history',
			'phone' => $phone
		);
		return $this->api_request($request);
	}

	/** 
	 * Do Action
	 *
	 * @return array API response object
	 */
	public function do_action($request) {

		$request += array('request' => 'do_action');

		return $this->api_request($request);
	}


	protected function api_request($payload = null)
	{
		$ch = curl_init($this->api_host);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

		$code = null;
		try {
			$result = curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$response = json_decode($result);

			if ($code != 200) {
				throw new APIError('Request was not successful', $code, $result, $response);
			}
		} catch (APIError $e) {
			$response = (object) array(
				'code' => $code,
				'status' => 'error',
				'success' => false,
				'exception' => $e
			);
		}

		curl_close($ch);

		return $response;
	}
}