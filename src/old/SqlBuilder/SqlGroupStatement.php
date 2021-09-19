<?php declare(strict_types = 1);

namespace alexisfayeulle\Builder\SqlBuilder;

class SqlGroupStatement
{

    public const COND_AND = 'AND';
    public const COND_OR = 'OR';

    public const COND_ALL = [
        self::COND_AND,
        self::COND_OR,
    ];

    /**
     * Variables
     */

    /** @var bool Encapsule the glue with space */
    protected $encapsule = true;

    /** @var string The glue of this group */
    protected $glue = '';

    /** @var string[] Statement array */
    protected $statement_list = [];

    /**
     * Getters
     */

    /**
     * Get statement list
     */
    public function getStatement(): array
    {
        return $this->statement_list;
    }

    /**
     * Get glue
     */
    public function getGlue(): string
    {
        return $this->glue;
    }

    /**
     * Get Encapsule
     */
    public function getEncapsule(): bool
    {
        return $this->encapsule;
    }

    /**
     * Setter
     */

    /**
     * Set the statement
     *
     * @param mixed[] $statement_list Statement list
     */
    public function setStatement(array $statement_list): self
    {
        $this->statement_list = $statement_list;

        return $this;
    }

    /**
     * Set the glue
     *
     * @param string $glue Glue
     */
    public function setGlue(string $glue): self
    {
        $this->glue = $glue;

        return $this;
    }

    /**
     * Set the glue
     *
     * @param string $glue Glue
     */
    public function setEncapsule(bool $encapsule): self
    {
        $this->encapsule = $encapsule;

        return $this;
    }

    /**
     * Adder
     */

    /**
     * Add a statement
     *
     * @param mixed $statement
     */
    public function addStatement($statement): self
    {
        $this->statement_list[] = $statement;

        return $this;
    }

    /**
     * Magics Functions
     */

    /**
     * To string function
     */
    public function __toString(): string
    {
        if ((empty($this->glue) && count($this->statement_list) > 1) || empty($this->statement_list)) {
            return '';
        }

        $return = [];

        foreach ($this->statement_list as $value) {
            $return[] = $value instanceof self ? '(' . $value . ')' : $value;
        }

        return implode($this->encapsule ? ' ' . $this->glue . ' ' : $this->glue, $return);
    }

}
