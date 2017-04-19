<?php
namespace torchlighttechnology\tests\glid;

use torchlighttechnology\glid\GlobalIdAPI;

use PHPUnit\Framework\TestCase;

class GlobalIdAPITest extends TestCase {

	private function mysetup() {
		$host = 'https://api.torchlighttechnology.com/glid/staging';
		$this->GlidAPI = new GlobalIdAPI($host);
	}
	
	/**
	* @dataProvider emailProvider
	*/
	public function testEmailLookup($email, $expectedHash) {
		$this->mysetup();

		$results = $this->GlidAPI->email_lookup($email);

		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash matches
		$this->assertEquals($expectedHash, $results->hash);	

	}

	/**
	* @dataProvider phoneEmailProvider
	*/
	public function testPhoneLookupByEmail($email, $expectedPhone) {
		$this->mysetup();

		$results = $this->GlidAPI->phone_lookup_by_email($email);

		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash matches
		$this->assertEquals($expectedPhone, $results->phone);

	}

	/**
	* @dataProvider phoneProvider
	*/
	public function testHashLookup($phone, $expectedHash){
		$this->mysetup();

		$results = $this->GlidAPI->hash_lookup($phone);

		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash matches
		$this->assertEquals($expectedHash, $results->hash);	

	}

	/**
	* @dataProvider phoneProvider
	*/
	public function testLastAction($phone, $expectedHash){
		$this->mysetup();

		$results = $this->GlidAPI->last_action($phone);
		
		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash matches
		$this->assertEquals($expectedHash, $results->hash);	

		// last_action attribute exists
		$this->assertTrue(isset($results->last_action));

	}

	/**
	* @dataProvider phoneProvider
	*/
	public function testHistory($phone, $expectedHash){
		$this->mysetup();

		$results = $this->GlidAPI->history($phone);
		
		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash matches
		$this->assertEquals($expectedHash, $results->hash);	

		// history attribute exists
		$this->assertTrue(isset($results->history));

	}

	/**
	* @dataProvider actionProvider
	*/
	public function testDoAction($phone, $action_type, $action_detail, $email, $timestamp, $value, $external_id, 
		$seller_id, $vertical, $remarketing, $sys){

		$this->mysetup();

		$request = [
			'phone' => $phone,
			'action_type' => $action_type,
			'action_detail' => $action_detail,
			'email' => $email,
			'timestamp' => $timestamp,
			'value' => $value,
			'external_id' => $external_id,
			'seller_id' => $seller_id,
			'vertical' => $vertical,
			'remarketing' => $remarketing,
			'sys' => $sys
		];

		$results = $this->GlidAPI->do_action($request);
		
		// Status is good
		$this->assertEquals('ok', $results->status);

		// Hash exists
		$this->assertTrue(isset($results->hash));

	}

	public function actionProvider() {
		return [
			['4102341299', 'data', 'cost', 'ag101@rida.com', '1488311258', '2.50', 99, 10, 'auto', null, 'pingtree'],
			['4102341299', 'data', 'revenue', 'ag101@rida.com', '1488311258', '3.50', 99, 10, 'auto', null, 'pingtree'],
			['4102341297', 'data', 'cost', 'ag102@rida.com', '1488311260', '2.50', 99, 10, 'auto', null, 'pingtree'],
			['4102341297', 'data', 'revenue', 'ag102@rida.com', '1488311260', '3.50', 99, 10, 'auto', null, 'pingtree']
		];
	}

	public function phoneProvider() {
		return [
			['4102341299', '3692acaa30028857d8420bbc1775ae14'],
			['4102341297', 'c1e74bf5ed82ee62f1dcf9f4980f77ec']
		];
	}

	public function emailProvider() {
		return [
			['ag101@rida.com', '3692acaa30028857d8420bbc1775ae14'],
			['ag102@rida.com', 'c1e74bf5ed82ee62f1dcf9f4980f77ec']
		];
	}

	public function phoneEmailProvider() {
		return [
			['ag101@rida.com', '4102341299'],
			['ag102@rida.com', '4102341297']
		];
	}
}