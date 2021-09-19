<?php

use alexisfayeulle\Builder\SqlBuilder;

require_once TEST_PKG_QUERYBUILDER . '/MyTestCase.php';

class SqlBuilderTest extends MyTestCase
{

    const DATABASE_NAME = 'my_database';
    const DATABASE_NAME_2 = 'my_second_database';

    const TABLE_NAME = 'users';
    const TABLE_NAME_2 = 'roles';

    const TABLE_DESCRIBE = [
        [
            'name' => 'UID',
            'type' => 'UInt16',
            'default_value' => '',
        ],
        [
            'name' => 'NAME',
            'type' => 'String',
            'default_value' => '',
        ],
        [
            'name' => 'STATUS',
            'type' => 'String',
            'default_value' => 'ToValidate',
        ],
        [
            'name' => 'SCORE',
            'type' => 'UInt32',
            'default_value' => '',
        ],
    ];
    const TABLE_FIXTURE = [
        [1, 'Guethenoc', 'OK', 12],
        [2, 'Roparzh', 'OK', 5],
        [4, 'Arthur', 'OK', 25],
        [5, 'Perseval', 'OK', 3],
        [6, 'Provensal', 'OK', 15],
        [7, 'Lancelot', 'OK', 24],
    ];

    const TABLE_DESCRIBE_2 = [
        [
            'name' => 'ID_ROLE',
            'type' => 'UInt32',
            'default_value' => '',
        ],
        [
            'name' => 'UID',
            'type' => 'UInt16',
            'default_value' => '',
        ],
        [
            'name' => 'NAME',
            'type' => 'String',
            'default_value' => 'ToValidate',
        ],
        [
            'name' => 'NIVEAU',
            'type' => 'UInt8',
            'default_value' => '',
        ],
    ];
    const TABLE_FIXTURE_2 = [
        [1, 1, 'Paysan', 1],
        [2, 2, 'Paysan', 1],
        [3, 3, 'Roi', 100],
        [4, 4, 'Seigneur', 75],
        [5, 5, 'Chevalier Heroique', 65],
        [6, 6, 'Chevalier Solitaire', 80],
    ];

    public function testInit()
    {
        $this->assertNotCatchError(static function() {
            $str = SRC_PKG_QUERYBUILDER;
        }, 'The const "SRC_PKG_QUERYBUILDER" does not exist');

        $this->assertNotCatchError(static function() {
            require_once SRC_PKG_QUERYBUILDER . '/SqlBuilder.php';
        }, 'The "require_once" for ' . SRC_PKG_QUERYBUILDER . '/SqlBuilder.php FAILED');
    }

    /**
     * @depends testInit
     */
    public function testConstruct()
    {
        $SqlBuilder = new SqlBuilder();

        $this->assertInstanceOf(SqlBuilder::class, $SqlBuilder);
    }

    /**
     * SELECT
     */

    /**
     * @depends testConstruct
     */
    public function testSelectEmpty()
    {
        $SqlBuilder = new SqlBuilder();
        $select = $SqlBuilder->select();

        $this->assertEmpty($select, 'This object is never Filled with anything');
    }

    /**
     * @depends testConstruct
     */
    public function testSelectJustDb()
    {
        $SqlBuilder = new SqlBuilder();
        $SqlBuilder->setDatabase(self::DATABASE_NAME);
        $select = $SqlBuilder->select();

        $this->assertEmpty($select, 'This object is never Filled with anything');
    }

    /**
     * @depends testConstruct
     */
    public function testSelectDbTable()
    {
        $SqlBuilder = new SqlBuilder();
        $SqlBuilder->setDatabase(self::DATABASE_NAME);
        $SqlBuilder->setTable(self::TABLE_NAME);
        $select = $SqlBuilder->select();

        $this->assertEquals('SELECT * FROM ', $select);
        $this->assertEmpty($select, 'This object is never Filled with anything');
    }

}
