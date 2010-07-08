<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage WSDL
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace Zend\Soap\WSDL\Strategy;

use Zend\Soap\WSDL\Strategy;

/**
 * Zend_Soap_WSDL_Strategy_AnyType
 *
 * @uses       \Zend\Soap\WSDL\Strategy\StrategyInterface
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage WSDL
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class AnyType implements Strategy
{
    /**
     * Not needed in this strategy.
     *
     * @param \Zend\Soap\WSDL $context
     */
    public function setContext(\Zend\Soap\WSDL $context)
    {

    }

    /**
     * Returns xsd:anyType regardless of the input.
     *
     * @param string $type
     * @return string
     */
    public function addComplexType($type)
    {
        return 'xsd:anyType';
    }
}
