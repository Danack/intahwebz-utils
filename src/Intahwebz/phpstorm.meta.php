<?php
namespace PHPSTORM_META {                                                 // we want to avoid the pollution

	/** @noinspection PhpUnusedLocalVariableInspection */                 // just to have a green code below
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	$STATIC_METHOD_TYPES = [                                              // we make sections for scopes
		\Mage::helper('') => [
			'core/string' instanceof \Mage_Core_Helper_String,
		],
		\Mage::getSingleton('') => [                                      // call to match
			'core/resource' instanceof \Mage_Core_Model_Resource,
		],
	];

	/** @noinspection PhpUnusedLocalVariableInspection */
	/** @noinspection PhpIllegalArrayKeyTypeInspection */
	$STATIC_METHOD_TYPES = [
		\Mage::getModel('') => [
			'catalog/product' instanceof \Mage_Catalog_Model_Product,     // argument value and return type
			'rating/rating' instanceof \Mage_Rating_Model_Rating,
		],
	];

}