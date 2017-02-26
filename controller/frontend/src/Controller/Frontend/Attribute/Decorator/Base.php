<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017
 * @package Controller
 * @subpackage Frontend
 */


namespace Aimeos\Controller\Frontend\Attribute\Decorator;


/**
 * Base for attribute frontend controller decorators
 *
 * @package Controller
 * @subpackage Frontend
 */
abstract class Base
	implements \Aimeos\Controller\Frontend\Common\Decorator\Iface
{
	private $context;
	private $controller;


	/**
	 * Initializes the controller decorator.
	 *
	 * @param \Aimeos\Controller\Frontend\Iface $controller Controller object
	 * @param \Aimeos\MShop\Context\Item\Iface $context Context object with required objects
	 */
	public function __construct( \Aimeos\Controller\Frontend\Iface $controller, \Aimeos\MShop\Context\Item\Iface $context )
	{
		$iface = '\Aimeos\Controller\Frontend\Attribute\Iface';
		if( !( $controller instanceof $iface ) )
		{
			$msg = sprintf( 'Class "%1$s" does not implement interface "%2$s"', get_class( $controller ), $iface );
			throw new \Aimeos\Controller\Frontend\Exception( $msg );
		}

		$this->context = $context;
		$this->controller = $controller;
	}


	/**
	 * Passes unknown methods to wrapped objects.
	 *
	 * @param string $name Name of the method
	 * @param array $param List of method parameter
	 * @return mixed Returns the value of the called method
	 * @throws \Aimeos\Controller\Frontend\Exception If method call failed
	 */
	public function __call( $name, array $param )
	{
		return @call_user_func_array( array( $this->controller, $name ), $param );
	}


	/**
	 * Returns the given search filter with the conditions attached for filtering by type code
	 *
	 * @param \Aimeos\MW\Criteria\Iface $filter Criteria object used for attribute search
	 * @param array $codes List of attribute type codes
	 * @return \Aimeos\MW\Criteria\Iface Criteria object containing the conditions for searching
	 * @since 2017.03
	 */
	public function addFilterTypes( \Aimeos\MW\Criteria\Iface $filter, array $codes )
	{
		return $this->controller->addFilterTypes( $filter, $codes );
	}


	/**
	 * Returns the default attribute filter
	 *
	 * @param boolean True to add default criteria
	 * @return \Aimeos\MW\Criteria\Iface Criteria object containing the conditions for searching
	 * @since 2017.03
	 */
	public function createFilter()
	{
		return $this->controller->createFilter();
	}


	/**
	 * Returns the attribute item for the given attribute ID
	 *
	 * @param string $id Unique attribute ID
	 * @param string[] $domains Domain names of items that are associated with the attributes and that should be fetched too
	 * @return \Aimeos\MShop\Attribute\Item\Iface Attribute item including the referenced domains items
	 * @since 2017.03
	 */
	public function getItem( $id, array $domains = array( 'media', 'price', 'text' ) )
	{
		return $this->controller->getItem( $id, $domains );
	}


	/**
	 * Returns the attributes filtered by the given criteria object
	 *
	 * @param \Aimeos\MW\Criteria\Iface $filter Critera object which contains the filter conditions
	 * @param string[] $domains Domain names of items that are associated with the attributes and that should be fetched too
	 * @param integer &$total Parameter where the total number of found attributes will be stored in
	 * @return array Ordered list of attribute items implementing \Aimeos\MShop\Attribute\Item\Iface
	 * @since 2017.03
	 */
	public function searchItems( \Aimeos\MW\Criteria\Iface $filter, array $domains = array( 'media', 'price', 'text' ), &$total = null )
	{
		return $this->controller->searchItems( $filter, $domains, $total );
	}


	/**
	 * Returns the context item
	 *
	 * @return \Aimeos\MShop\Context\Item\Iface Context item object
	 */
	protected function getContext()
	{
		return $this->context;
	}


	/**
	 * Returns the frontend controller
	 *
	 * @return \Aimeos\Controller\Frontend\Attribute\Iface Frontend controller object
	 */
	protected function getController()
	{
		return $this->controller;
	}
}
