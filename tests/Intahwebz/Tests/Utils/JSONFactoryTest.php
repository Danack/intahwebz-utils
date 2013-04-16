<?php


namespace Intahwebz\Tests\Utils;

use Intahwebz\Utils\JSONFactory;
use Intahwebz\Tests\Utils\IntahwebzUtilsTestCase;
use Intahwebz\Tests\Utils\JSONFactoryImplementation;


class JSONFactoryTest extends IntahwebzUtilsTestCase {

	public function testConversion() {
		$testObject = new JSONFactoryImplementation();
		$testObject->init('public', 'private');
		$jsonData = $testObject->toJSON();
		$compareObject = JSONFactoryImplementation::fromJSON($jsonData);

		$this->assertAttributeEquals('public', 'publicVar', $compareObject);
		$this->assertAttributeEquals(null, 'privateVar', $compareObject);
	}

	public function testEmbeddedObject() {

		$embeddedObject = new JSONFactoryImplementation();

		$embeddedObject->init('public', 'private');

		$testObject = new JSONFactoryImplementation();
		$testObject->init($embeddedObject, 'private');

		$jsonData = $testObject->toJSON();
		//$compareObject = JSONFactoryImplementation::fromJSON($jsonData);
		$compareObject = json_decode_object($jsonData);

		$embeddedCompareObject = $compareObject->getPublic();

		$this->assertAttributeEquals('public', 'publicVar', $embeddedCompareObject);
		$this->assertAttributeEquals(null, 'privateVar', $embeddedCompareObject);
	}

}




?>