<?php

require_once TEST_PKG_QUERYBUILDER . '/MyTestCase.php';

use alexisfayeulle\Builder\SqlBuilder\SqlCondition;

class Sql_Test extends MyTestCase
{

    /*
    public function testInit()
    {
        $this->assertNotCatchError(static function() {
            $str = SRC_PKG_QUERYBUILDER;
        }, 'The const "SRC_PKG_QUERYBUILDER" does not exist');

        $this->assertNotCatchError(static function() {
            require_once SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlSelect.php';
        }, 'The "require_once" for ' . SRC_PKG_QUERYBUILDER . '/SqlBuilder/SqlSelect.php FAILED');
    }
    */

    /**
     * Test values
     */

    /**
     * @depends testInit
     */
    /*
    public function testConstruct()
    {
        $SqlSelect = new SqlSelect();

        $this->assertInstanceOf(SqlSelect::class, $SqlSelect);
    }
    */

    /**
     * @depends testConstruct
     */
    /*
    public function testField()
    {
        $SqlSelect = new SqlSelect();

        $SqlSelect->setFields();
    }
    */

}
