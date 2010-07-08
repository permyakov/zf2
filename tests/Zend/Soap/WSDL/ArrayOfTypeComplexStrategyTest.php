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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @namespace
 */
namespace ZendTest\Soap\WSDL;

require_once __DIR__."/../TestAsset/commontypes.php";

use Zend\Soap\WSDL;


/**
 * @category   Zend
 * @package    Zend_Soap
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Soap
 * @group      Zend_Soap_WSDL
 */
class ArrayOfTypeComplexStrategyTest extends \PHPUnit_Framework_TestCase
{
    private $wsdl;
    private $strategy;

    public function setUp()
    {
        $this->strategy = new \Zend\Soap\WSDL\Strategy\ArrayOfTypeComplex();
        $this->wsdl = new WSDL('MyService', 'http://localhost/MyService.php', $this->strategy);
    }

    public function testNestingObjectsDeepMakesNoSenseThrowingException()
    {
        $this->setExpectedException('Zend\Soap\WSDLException');
        $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexTest[][]');
    }

    public function testAddComplexTypeOfNonExistingClassThrowsException()
    {
        $this->setExpectedException('Zend\Soap\WSDLException');
        $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_UnknownClass[]');
    }

    /**
     * @group ZF-5046
     */
    public function testArrayOfSimpleObject()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexTest[]');
        $this->assertEquals("tns:ArrayOfZendTest_Soap_TestAsset_ComplexTest", $return);

        $wsdl = $this->wsdl->toXML();

        $this->assertContains(
            '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexTest"><xsd:complexContent><xsd:restriction base="soap-enc:Array"><xsd:attribute ref="soap-enc:arrayType" wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexTest[]"/></xsd:restriction></xsd:complexContent></xsd:complexType>',
            $wsdl,
            $wsdl
        );

        $this->assertContains(
            '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexTest"><xsd:all><xsd:element name="var" type="xsd:int"/></xsd:all></xsd:complexType>',
            $wsdl
        );
    }

    public function testThatOverridingStrategyIsReset()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexTest[]');
        $this->assertEquals("tns:ArrayOfZendTest_Soap_TestAsset_ComplexTest", $return);
        // $this->assertTrue($this->wsdl->getComplexTypeStrategy() instanceof \Zend\Soap\WSDL\Strategy\ArrayOfTypeComplexStrategy);

        $wsdl = $this->wsdl->toXML();
    }

    /**
     * @group ZF-5046
     */
    public function testArrayOfComplexObjects()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectStructure[]');
        $this->assertEquals("tns:ArrayOfZendTest_Soap_TestAsset_ComplexObjectStructure", $return);

        $wsdl = $this->wsdl->toXML();

        $this->assertContains(
            '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexObjectStructure"><xsd:complexContent><xsd:restriction base="soap-enc:Array"><xsd:attribute ref="soap-enc:arrayType" wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexObjectStructure[]"/></xsd:restriction></xsd:complexContent></xsd:complexType>',
            $wsdl,
            $wsdl
        );

        $this->assertContains(
            '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexObjectStructure"><xsd:all><xsd:element name="boolean" type="xsd:boolean"/><xsd:element name="string" type="xsd:string"/><xsd:element name="int" type="xsd:int"/><xsd:element name="array" type="soap-enc:Array"/></xsd:all></xsd:complexType>',
            $wsdl
        );
    }

    public function testArrayOfObjectWithObject()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]');
        $this->assertEquals("tns:ArrayOfZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure", $return);

        $wsdl = $this->wsdl->toXML();

        $this->assertContains(
            '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure"><xsd:complexContent><xsd:restriction base="soap-enc:Array"><xsd:attribute ref="soap-enc:arrayType" wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]"/></xsd:restriction></xsd:complexContent></xsd:complexType>',
            $wsdl
        );

        $this->assertContains(
            '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure"><xsd:all><xsd:element name="object" type="tns:ZendTest_Soap_TestAsset_ComplexTest"/></xsd:all></xsd:complexType>',
            $wsdl,
            $wsdl
        );

        $this->assertContains(
            '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexTest"><xsd:all><xsd:element name="var" type="xsd:int"/></xsd:all></xsd:complexType>',
            $wsdl
        );
    }

    /**
     * @group ZF-4937
     */
    public function testAddingTypesMultipleTimesIsSavedOnlyOnce()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]');
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]');

        $wsdl = $this->wsdl->toXML();

        $this->assertEquals(1,
            substr_count($wsdl, 'wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]"')
        );
        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure">')
        );
        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexTest">')
        );
    }

    /**
     * @group ZF-4937
     */
    public function testAddingSingularThenArrayTypeIsRecognizedCorretly()
    {
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure');
        $return = $this->wsdl->addComplexType('ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]');

        $wsdl = $this->wsdl->toXML();

        $this->assertEquals(1,
            substr_count($wsdl, 'wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure[]"')
        );
        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexObjectWithObjectStructure">')
        );
        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexTest">')
        );
    }

    /**
     * @group ZF-5149
     */
    public function testArrayOfComplexNestedObjectsIsCoveredByStrategyAndNotThrowingException()
    {
        try {
            $return = $this->wsdl->addComplexType("ZendTest_Soap_TestAsset_ComplexTypeA");
            $wsdl = $this->wsdl->toXml();
        } catch(\Exception $e) {
            $this->fail("Adding object with nested structure should not throw exception.");
        }
    }

    /**
     * @group ZF-5149
     */
    public function testArrayOfComplexNestedObjectsIsCoveredByStrategyAndAddsAllTypesRecursivly()
    {
        $return = $this->wsdl->addComplexType("ZendTest_Soap_TestAsset_ComplexTypeA");
        $wsdl = $this->wsdl->toXml();

        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ZendTest_Soap_TestAsset_ComplexTypeA">'),
            'No definition of complex type A found.'
        );
        $this->assertEquals(1,
            substr_count($wsdl, '<xsd:complexType name="ArrayOfZendTest_Soap_TestAsset_ComplexTypeB">'),
            'No definition of complex type B array found.'
        );
        $this->assertEquals(1,
            substr_count($wsdl, 'wsdl:arrayType="tns:ZendTest_Soap_TestAsset_ComplexTypeB[]"'),
            'No usage of Complex Type B array found.'
        );
    }

    /**
     * @group ZF-5754
     */
    public function testNestingOfSameTypesDoesNotLeadToInfiniteRecursionButWillThrowException()
    {
        $this->setExpectedException('Zend\Soap\WSDLException', 'Infinite recursion');
        $return = $this->wsdl->addComplexType("ZendTest_Soap_TestAsset_Recursion");
    }
}
