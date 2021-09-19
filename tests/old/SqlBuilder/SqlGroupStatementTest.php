<?php

require_once TEST_PKG_QUERYBUILDER . '/MyTestCase.php';

use alexisfayeulle\Builder\SqlBuilder\SqlGroupStatement;

class SqlGroupStatementTest extends MyTestCase
{

    public function testInit()
    {
        $this->assertNotCatchError(static function() {
            $str = SRC_PKG_QUERYBUILDER;
        }, 'The const "SRC_PKG_QUERYBUILDER" does not exist');

        $this->assertNotCatchError(static function() {
            require_once SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlGroupStatement.php';
        }, 'The "require_once" for ' . SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlGroupStatement.php FAILED');
    }

    /**
     * Test values
     */

    /**
     * @depends testInit
     */
    public function testConstruct()
    {
        $SqlGroupStatement = new SqlGroupStatement();

        $this->assertInstanceOf(SqlGroupStatement::class, $SqlGroupStatement);
    }

    /**
     * @depends testConstruct
     */
    public function testToString()
    {
        $SqlGroupStatement = new SqlGroupStatement();

        $this->assertTrue(method_exists($SqlGroupStatement, '__toString'), 'The __toString method does not exist');
        $this->assertIsString($SqlGroupStatement->__toString(), 'Call __toString does not return string');
        $this->assertIsString((string) $SqlGroupStatement, 'Casting in string does not return string');
    }

    /**
     * @depends testToString
     */
    public function testAddStatementReturnSelf()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $self = $SqlGroupStatement->addStatement('test');

        $this->assertEquals($SqlGroupStatement, $self);
    }

    /**
     * @depends testToString
     */
    public function testSetGlueReturnSelf()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $self = $SqlGroupStatement->setGlue('test');

        $this->assertEquals($SqlGroupStatement, $self);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testUseCaseGroupOfOneNoGlue()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement->addStatement('test');

        $str = (string) $SqlGroupStatement;

        $this->assertNotEmpty($str);
        $this->assertEquals('test', $str);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testUseCaseGroupOfOneEmptyGlueInChain()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->addStatement('test')
            ->setGlue('')
        ;

        $str = (string) $SqlGroupStatement;

        $this->assertNotEmpty($str);
        $this->assertEquals('test', $str);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testUseCaseGroupOfOneWithGlueInChain()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->setGlue('A')
            ->addStatement('test')
        ;

        $str = (string) $SqlGroupStatement;

        $this->assertNotEmpty($str);
        $this->assertEquals('test', $str);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testUseCaseGroupOfTwoNoGlueInChain()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->addStatement('test1')
            ->addStatement('test2')
        ;

        $this->assertEmpty((string) $SqlGroupStatement);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testUseCaseGroupOfTwoWithGlueInChain()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->addStatement('test1')
            ->setGlue('A')
            ->addStatement('test2')
        ;

        $str = (string) $SqlGroupStatement;

        $this->assertNotEmpty($str);
        $this->assertEquals('test1 A test2', $str);
    }

    /**
     * @depends testSetGlueReturnSelf
     * @depends testAddStatementReturnSelf
     */
    public function testSetEncapsuleReturnSelf()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $self = $SqlGroupStatement->setEncapsule(false);

        $this->assertEquals($SqlGroupStatement, $self);
    }

    /**
     * @depends testSetEncapsuleReturnSelf
     */
    public function testUseCaseGroupOfTwoWithGlueInChainNoEncaps()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->addStatement('test1')
            ->setGlue('A')
            ->setEncapsule(false)
            ->addStatement('test2')
        ;

        $str = (string) $SqlGroupStatement;

        $this->assertNotEmpty($str);
        $this->assertEquals('test1Atest2', $str);
    }

    /**
     * @depends testSetEncapsuleReturnSelf
     */
    public function testUseCaseSubGroup()
    {
        $SqlGroupStatement1 = new SqlGroupStatement();
        $SqlGroupStatement1
            ->addStatement('test1')
            ->setGlue('A')
            ->addStatement('test2')
            ->setEncapsule(true)
        ;

        $SqlGroupStatement2 = new SqlGroupStatement();
        $SqlGroupStatement2
            ->addStatement('test3')
            ->setGlue('B')
            ->addStatement('test4')
            ->setEncapsule(true)
            ->addStatement($SqlGroupStatement1)
        ;

        $str1 = (string) $SqlGroupStatement1;
        $exepted1 = 'test1 A test2';

        $this->assertNotEmpty($str1);
        $this->assertEquals($exepted1, $str1);

        $str2 = (string) $SqlGroupStatement2;

        $this->assertNotEmpty($str2);
        $this->assertEquals('test3 B test4 B (' . $exepted1 . ')', $str2);
    }

    /**
     * @depends testSetEncapsuleReturnSelf
     */
    public function testUseCaseSubGroup2()
    {
        $SqlGroupStatement1 = new SqlGroupStatement();
        $SqlGroupStatement1
            ->addStatement('USER = 2')
            ->setGlue('AND')
            ->addStatement('STATUS = \'active\'')
            ->setEncapsule(true)
        ;

        $SqlGroupStatement2 = new SqlGroupStatement();
        $SqlGroupStatement2
            ->addStatement('RID = 1')
            ->setGlue('OR')
            ->addStatement('RID = 2')
            ->setEncapsule(true)
            ->addStatement($SqlGroupStatement1)
        ;

        $str1 = (string) $SqlGroupStatement1;
        $exepted1 = 'USER = 2 AND STATUS = \'active\'';

        $this->assertEquals($exepted1, $str1);

        $str2 = (string) $SqlGroupStatement2;

        $this->assertEquals('RID = 1 OR RID = 2 OR (' . $exepted1 . ')', $str2);
    }

    /**
     * @depends testSetEncapsuleReturnSelf
     */
    public function testUseCaseOrderBy()
    {
        $SqlGroupStatement = new SqlGroupStatement();
        $SqlGroupStatement
            ->setGlue(', ')
            ->setEncapsule(false)
            ->addStatement('UID ASC')
            ->addStatement('STATUS DESC')
        ;

        $this->assertEquals('UID ASC, STATUS DESC', (string) $SqlGroupStatement);
    }

}
