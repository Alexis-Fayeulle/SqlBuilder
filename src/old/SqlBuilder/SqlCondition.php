<?php declare(strict_types = 1);

namespace alexisfayeulle\Builder\SqlBuilder;

class SqlCondition
{

    public const COND_EQUAL = '=';
    public const COND_NOT_EQUAL = '!=';
    public const COND_IN = 'IN';
    public const COND_NOT_IN = 'NOT IN';
    public const COND_SUPERIOR = '>';
    public const COND_SUPERIOR_EQUAL = '>=';
    public const COND_INFERIOR = '<';
    public const COND_INFERIOR_EQUAL = '<=';
    public const COND_IS_NULL = 'IS NULL';
    public const COND_IS_NOT_NULL = 'IS NOT NULL';

    public const COND_ALL = [
        self::COND_EQUAL,
        self::COND_NOT_EQUAL,
        self::COND_IN,
        self::COND_NOT_IN,
        self::COND_SUPERIOR,
        self::COND_SUPERIOR_EQUAL,
        self::COND_INFERIOR,
        self::COND_INFERIOR_EQUAL,
    ];

    /**
     * Variables
     */

    /** @var string The field in condition */
    private $field = '';

    /** @var string The condition operator */
    private $operator = '';

    /** @var mixed The value to compare, can be object */
    private $value = null;

    /**
     * Getters
     */

    /**
     * Return The field in condition
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Return The condition operator
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * Return The value to compare, can be object
     */
    public function getValue(): string
    {
        return $this->operator;
    }

    /**
     * Setters
     */

    /**
     * Set The field in condition
     *
     * @param string $prm_field The new field in condition
     */
    public function setField(string $prm_field): self
    {
        $regex = '/^[a-zA-Z_][a-zA-Z0-9_]*$/';

        if (preg_match($regex, $prm_field)) {
            $this->field = $prm_field;
        }

        return $this;
    }

    /**
     * Set The field in condition
     *
     * @param string $prm_field The new field in condition
     */
    public function setOperator(string $prm_operator): self
    {
        if (in_array($prm_operator, self::COND_ALL, true)) {
            $this->operator = $prm_operator;
        }

        return $this;
    }

    /**
     * Set The field in condition
     *
     * @param string $prm_value The new field in condition
     */
    public function setValue($prm_value): self
    {
        $this->value = $prm_value;

        return $this;
    }

    /**
     * Adders
     */

    /**
     * Prepare Functions
     */

    public function prepareValue($in): string
    {
        if (is_int($in) || is_float($in)) {
            return (string) $in;
        }

        if (is_string($in) || (is_object($in) && method_exists($in, '__toString'))) {
            return '\'' . ((string) $in) . '\'';
        }

        return '';
    }

    /**
     * Functions
     */

    /**
     * Constructor
     *
     * @param string $prm_field Field name
     * @param string $prm_op Operation in list of operation
     * @param mixed $prm_value Any value to compare
     */
    public function __construct(?string $prm_field = '', ?string $prm_op = '', $prm_value = null)
    {
        $this
            ->setField($prm_field)
            ->setOperator($prm_op)
            ->setValue($prm_value)
        ;
    }

    public function __toString(): string
    {
        $is_int_float_string_object_w_toString = (
            is_int($this->value) ||
            is_float($this->value) ||
            is_string($this->value) ||
            (is_object($this->value) && method_exists($this->value, '__toString'))
        );

        if (
            empty($this->field) ||
            empty($this->operator) ||
            (
                empty($this->value) &&
                !in_array($this->operator, [self::COND_IS_NULL, self::COND_IS_NOT_NULL], true)
            ) ||
            (
                $is_int_float_string_object_w_toString &&
                !in_array($this->operator, [self::COND_EQUAL, self::COND_NOT_EQUAL, self::COND_INFERIOR, self::COND_INFERIOR_EQUAL, self::COND_SUPERIOR, self::COND_SUPERIOR_EQUAL, self::COND_IN, self::COND_NOT_IN], true)
            ) ||
            (
                is_array($this->value) &&
                !in_array($this->operator, [self::COND_IN, self::COND_NOT_IN], true)
            )
        ) {
            // error if

            // $this->field is empty
            // $this->operator is empty

            // $this->value is empty
            //      AND $this->operator not in self::COND_IS_NULL, self::COND_IS_NOT_NULL

            // $this->value is int, float, is_string, is_object with __toString
            //      AND $this->operator not in IN, NOT_IN, EQUAL, NOT_EQUAL, INFERIOR, INFERIOR_EQUAL, SUPERIOR, SUPERIOR_EQUAL

            // $this->value is array
            //      AND $this->operator not in IN, NOT_IN

            return '';
        }

        $prep = '';

        if (is_array($this->value) && array_sum(array_map('is_array', $this->value)) === 0) {
            $map = [];

            foreach ($this->value as $value) {
                $map[] = $this->prepareValue($value);
            }

            $prep = implode(', ', $map);
        } else {
            $prep = $this->prepareValue($this->value);
        }

        $ret = $this->field . ' ' . $this->operator;

        if (
            !in_array($this->operator, [
                self::COND_IS_NULL,
                self::COND_IS_NOT_NULL,
            ], true)
        ) {
            if (
                in_array($this->operator, [
                    self::COND_IN,
                    self::COND_NOT_IN,
                ], true)
            ) {
                $prep = '(' . $prep . ')';
            }

            $ret .= ' ' . $prep;
        }

        return $ret;
    }

}
