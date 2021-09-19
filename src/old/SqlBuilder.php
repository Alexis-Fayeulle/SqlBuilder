<?php declare(strict_types = 1);

namespace alexisfayeulle\Builder;

use alexisfayeulle\Builder\SqlBuilder\SqlCondition;
use alexisfayeulle\Builder\SqlBuilder\SqlGroupStatement;

class SqlBuilder
{

    /**
     * Constants
     */

    /** @var string The default condition if $this->where is empty */
    const DEFAULT_CONDITION_WHERE = SqlGroupStatement::COND_AND;

    /**
     * Variables
     */

    /** @var string */
    protected $database = '';

    /** @var string */
    protected $table = '';

    /** @var string[] */
    protected $fields = [];

    /** @var ?SqlGroupStatement Condition */
    protected $where = null;

    /** @var ?SqlGroupStatement Condition */
    protected $having = null;

    /** @var ?SqlGroupStatement Expression */
    protected $groupby = null;

    /** @var ?SqlGroupStatement Expression */
    protected $orderby = null;

    /** @var null|SqlBuilder|string[] Expression */
    protected $values = null;

    /** @var ?int */
    protected $limit = null;

    /** @var ?int */
    protected $offset = null;

    /**
     * Getters
     */

    /**
     * Return Database
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * Return Table
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Return Table
     *
     * @return string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Return Table
     */
    public function getWhere(): ?SqlGroupStatement
    {
        return $this->where;
    }

    /**
     * Return Having
     */
    public function getHaving(): ?SqlGroupStatement
    {
        return $this->having;
    }

    /**
     * Return Group By
     */
    public function getGroupBy(): ?SqlGroupStatement
    {
        return $this->groupby;
    }

    /**
     * Return Order by
     */
    public function getOrderBy(): ?SqlGroupStatement
    {
        return $this->orderby;
    }

    /**
     * Return Values for insert
     *
     * @return mixed[][]|SqlBuilder
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Return Limit
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Return Offset
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    /**
     * Setters
     */

    /**
     * Set Database
     *
     * @param string $prm Database
     */
    public function setDatabase(string $prm): self
    {
        $this->database = $prm;

        return $this;
    }

    /**
     * Set Table
     *
     * @param string $prm Table
     */
    public function setTable(string $prm): self
    {
        $this->table = $prm;

        return $this;
    }

    /**
     * Set Fields
     *
     * @param string[] $prm Fields
     */
    public function setFields(array $prm): self
    {
        $this->fields = $prm;

        return $this;
    }

    /**
     * Set Where
     *
     * @param SqlGroupStatement $prm Where
     */
    public function setWhere(SqlGroupStatement $prm): self
    {
        $this->where = $prm;

        return $this;
    }

    /**
     * Set Having
     *
     * @param SqlGroupStatement $prm Having
     */
    public function setHaving(SqlGroupStatement $prm): self
    {
        $this->having = $prm;

        return $this;
    }

    /**
     * Set Group By
     *
     * @param SqlGroupStatement $prm Group By
     */
    public function setGroupBy(SqlGroupStatement $prm): self
    {
        $this->groupby = $prm;
        $this->groupby->setGlue(', ')->setEncapsule(false);

        return $this;
    }

    /**
     * Set Order By
     *
     * @param SqlGroupStatement $prm Order By
     */
    public function setOrderBy(SqlGroupStatement $prm): self
    {
        $this->orderby = $prm;
        $this->orderby->setGlue(', ')->setEncapsule(false);

        return $this;
    }

    /**
     * Set Values for insert
     *
     * @param mixed[][]|SqlBuilder $prm Values
     */
    public function setValues($prm): self
    {
        $this->values = $prm;

        return $this;
    }

    /**
     * Set Limit
     *
     * @param int $prm Limit
     */
    public function setLimit(int $prm): self
    {
        $this->limit = $prm;

        return $this;
    }

    /**
     * Set Offset
     *
     * @param int $prm Offset
     */
    public function setOffset(int $prm): self
    {
        $this->offset = $prm;

        return $this;
    }

    /**
     * Adders
     */

    /**
     * Add Field
     *
     * @param string|string[] $prm Field(s)
     */
    public function addFields($prm): self
    {
        if (!is_array($prm)) {
            $prm = [$prm];
        }

        foreach ($prm as $v) {
            $this->fields[] = $v;
        }

        return $this;
    }

    /**
     * Add Field
     *
     * @param (SqlGroupStatement|SqlCondition) $prm Where(s)
     */
    public function addWhere($prm): self
    {
        if ($prm instanceof SqlCondition) {
            if (empty($this->where)) {
                $this->where = (new SqlGroupStatement())->setGlue(self::DEFAULT_CONDITION_WHERE);
            }

            $this->where->addStatement($prm);
        }

        if ($prm instanceof SqlGroupStatement) {
            $this->setWhere($prm);
        }

        return $this;
    }

    /**
     * Add Having
     *
     * @param (SqlGroupStatement|SqlCondition)[] $prm Where(s)
     */
    public function addHaving($prm): self
    {
        if ($prm instanceof SqlCondition) {
            if (empty($this->having)) {
                $this->having = (new SqlGroupStatement())->setGlue(self::DEFAULT_CONDITION_WHERE);
            }

            $this->having->addStatement($prm);
        }

        if ($prm instanceof SqlGroupStatement) {
            $this->having = $prm;
        }

        return $this;
    }

    /**
     * Add Group By
     *
     * @param string|string[] $prm Where(s)
     */
    public function addGroupBy($prm): self
    {
        if (empty($this->groupby)) {
            $this->setGroupBy((new SqlGroupStatement()));
        }

        if (!is_array($prm)) {
            $prm = [$prm];
        }

        foreach ($prm as $v) {
            if (!is_string($v) && !is_numeric($v)) {
                continue;
            }

            $this->groupby->addStatement($v);
        }

        return $this;
    }

    /**
     * Add Order By
     *
     * @param string|string[] $prm Where(s)
     */
    public function addOrderBy($prm): self
    {
        if (empty($this->orderby)) {
            $this->setOrderBy((new SqlGroupStatement()));
        }

        if (!is_array($prm)) {
            $prm = [$prm];
        }

        foreach ($prm as $v) {
            if (!is_string($v) && !is_numeric($v)) {
                continue;
            }

            $this->orderby->addStatement($v);
        }

        return $this;
    }

    /**
     * Add Values for insert
     *
     * @param SqlBuilder|mixed[][] $prm Values
     */
    public function addValues($prm, bool $is_list_of_rows = false): self
    {
        if ($prm instanceof SqlBuilder) {
            $this->values = $prm;
        } else {
            if (!$is_list_of_rows) {
                $prm = [$prm];
            }

            foreach ($prm as $v) {
                $this->values[] = $v;
            }
        }

        return $this;
    }

    /**
     * Prepare Functions
     */

    /**
     * Concat database and table
     */
    protected function prepareDbTable(): string
    {
        return $this->database . '.' . $this->table;
    }

    /**
     * Concat 'FROM ' database and table
     */
    protected function prepareFrom(): string
    {
        return 'FROM ' . $this->prepareDbTable();
    }

    /**
     * Concat Where clause
     */
    protected function prepareWhere(): string
    {
        $str = $this->where . '';

        if (empty($str)) {
            return '';
        }

        return 'WHERE ' . $str;
    }

    /**
     * Concat Having clause
     */
    protected function prepareHaving(): string
    {
        $str = $this->having . '';

        if (empty($str)) {
            return '';
        }

        return 'HAVING ' . $str;
    }

    /**
     * Concat Having clause
     */
    protected function prepareGroupBy(): string
    {
        $str = $this->groupby . '';

        if (empty($str)) {
            return '';
        }

        return 'GROUP BY ' . $str;
    }

    /**
     * Concat Having clause
     */
    protected function prepareOrderBy(): string
    {
        $str = $this->orderby . '';

        if (empty($str)) {
            return '';
        }

        return 'ORDER BY ' . $str;
    }

    /**
     * Concat Limit Offset
     */
    protected function prepareLimitOffset(): string
    {
        if (empty($this->limit) && $this->limit !== 0) {
            return '';
        }

        $str = 'LIMIT ' . $this->limit;

        if (empty($this->offset) && $this->offset !== 0) {
            return $str;
        }

        return $str . ' ' . 'OFFSET ' . $this->offset;
    }

    /**
     * Mains Functions
     */

    /**
     * Select SQL
     */
    public function select(): string
    {
        return implode(' ', array_filter([
            'SELECT ' . implode(', ', $this->fields),
            $this->prepareFrom(),
            $this->prepareWhere(),
            $this->prepareGroupBy(),
            $this->prepareHaving(),
            $this->prepareOrderBy(),
            $this->prepareLimitOffset(),
        ])) . ';';
    }

    /**
     * Insert SQL
     */
    public function insert(): string
    {
        $this_ = &$this;
        $str = 'INSERT INTO ' . $this->prepareDbTable() . ' ';

        if (!empty($this->fields)) {
            $str .= '(' . implode(', ', $this->fields) . ') ';
        }

        if ($this->values instanceof SqlBuilder) {
            return $str . $this->values->select();
        } else {
            $matrix_insert = [];

            foreach ($this->values as $a_document) {
                $parsed_document = array_map(static function($x) use ($this_) {
                    return $this_->parseDocumentField($x);
                }, $a_document ?? []);

                $matrix_insert[] = implode(', ', $parsed_document);
            }

            return $str . 'VALUES (' . implode('), (', $matrix_insert) . ');';
        }
    }

    /**
     * Delete SQL
     */
    public function delete(): string
    {
        return 'DELETE ' . $this->prepareFrom() . ' ' . $this->prepareWhere() . ';';
    }

    /**
     * Update SQL
     *
     * @var string[] $data Key value array with keys the field and value the new value
     */
    public function update(array $data): string
    {
        $str_SET = [];

        foreach ($data as $key => $value) {
            $str_SET[] = $key . ' = ' . $this->parseDocumentField($value);
        }

        return 'UPDATE ' . $this->prepareDbTable() . ' SET ' . implode(', ', $str_SET) . $this->prepareWhere() . ';';
    }

    /**
     * Magics Functions
     */

    /**
     * Constructor
     *
     * @param string $database Database
     * @param string $table Table
     */
    public function __construct(string $table = '', string $database = '')
    {
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Utils Functions
     */

    /**
     * In insert case and for each element in matrix / in document, this is the function to parse an element
     *
     * @param mixed $field
     */
    protected function parseDocumentField($field): string
    {
        if (is_numeric($field)) {
            return strval($field);
        }

        if (is_string($field)) {
            return '\'' . $field . '\'';
        }

        return '';
    }

}
