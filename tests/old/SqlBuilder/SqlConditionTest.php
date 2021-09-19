<?php

require_once TEST_PKG_QUERYBUILDER . '/MyTestCase.php';

use alexisfayeulle\Builder\SqlBuilder\SqlCondition;

class SqlConditionTest extends MyTestCase
{

    public function testInit()
    {
        $this->assertNotCatchError(static function() {
            $str = SRC_PKG_QUERYBUILDER;
        }, 'The const "SRC_PKG_QUERYBUILDER" does not exist');

        $this->assertNotCatchError(static function() {
            require_once SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlCondition.php';
        }, 'The "require_once" for ' . SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlCondition.php FAILED');
    }

    /**
     * Test values
     */

    /**
     * @depends testInit
     */
    public function testConstruct()
    {
        $SqlCondition = new SqlCondition();

        $this->assertInstanceOf(SqlCondition::class, $SqlCondition);
    }

    /**
     * @depends testConstruct
     */
    public function testField()
    {
        $SqlCondition = new SqlCondition('A');
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case \'A\' void void');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldFakeOperator()
    {
        $SqlCondition = new SqlCondition('A', 'fake');
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case \'A\' \'fake\' void');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperator()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_EQUAL);
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case \'A\' SqlCondition::COND_EQUAL void');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorValueInt()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_EQUAL, 1);
        $str = $SqlCondition . '';

        $this->assertEquals('A = 1', $str, 'Use case \'A\' SqlCondition::COND_EQUAL 1');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldIntOperatorValueInt()
    {
        $SqlCondition = new SqlCondition(1234, SqlCondition::COND_EQUAL, 1);
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case 1234 SqlCondition::COND_EQUAL 1');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldIntInStringOperatorValueInt()
    {
        $SqlCondition = new SqlCondition('1234', SqlCondition::COND_EQUAL, 1);
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case \'1234\' SqlCondition::COND_EQUAL 1');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorValueIntInString()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_EQUAL, '1');
        $str = $SqlCondition . '';

        $this->assertEquals('A = \'1\'', $str, 'Use case \'A\' SqlCondition::COND_EQUAL \'1\'');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorValueString()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_EQUAL, 'test');
        $str = $SqlCondition . '';

        $this->assertEquals('A = \'test\'', $str, 'Use case \'A\' SqlCondition::COND_EQUAL \'test\'');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorValueArray()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_EQUAL, [1, 2, 3]);
        $str = $SqlCondition . '';

        $this->assertEquals('', $str, 'Use case \'A\' SqlCondition::COND_EQUAL [1, 2, 3]');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueInt()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, 1);
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (1)', $str, 'Use case \'A\' SqlCondition::COND_IN 1');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueIntInString()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, '1');
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (\'1\')', $str, 'Use case \'A\' SqlCondition::COND_IN \'1\'');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueString()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, 'test');
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (\'test\')', $str, 'Use case \'A\' SqlCondition::COND_IN \'test\'');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueArrayInt()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, [1, 2, 3]);
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (1, 2, 3)', $str, 'Use case \'A\' SqlCondition::COND_IN [1, 2, 3]');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueArrayString()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, ['foo', 'bar', 'baz']);
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (\'foo\', \'bar\', \'baz\')', $str, 'Use case \'A\' SqlCondition::COND_IN [\'foo\', \'bar\', \'baz\']');
    }

    /**
     * @depends testConstruct
     */
    public function testFieldOperatorInValueArrayMixed()
    {
        $SqlCondition = new SqlCondition('A', SqlCondition::COND_IN, ['foo', 1, '2']);
        $str = $SqlCondition . '';

        $this->assertEquals('A IN (\'foo\', 1, \'2\')', $str, 'Use case \'A\' SqlCondition::COND_IN [\'foo\', 1, \'2\']');
    }

}
