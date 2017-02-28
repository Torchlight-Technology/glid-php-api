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
			['9192550928', 'data', 'cost', '', '1488311258', '2.50', 99, 10, 'auto', null, 'pingtree'],
			['9192550928', 'data', 'revenue', '', '1488311258', '3.50', 99, 10, 'auto', null, 'pingtree']
		];
	}

	public function phoneProvider() {
		return [
			['9192550928', 'b6dba49f5562f833951b59291a4db463'],
			['7142623064', 'c68513ba037a806a8e159a41a39e055b']
		];
	}

	public function emailProvider() {
		return [
			['loverodney91@gmail.com', 'b6dba49f5562f833951b59291a4db463'],
			['kjsyed42@yahoo.com', 'c68513ba037a806a8e159a41a39e055b']
		];
	}
}