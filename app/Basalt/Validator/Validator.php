<?php

namespace Basalt\Validator;

/*
 * Validator, RuleInterface and Base are written by @pomek {@url http://github.com/pomek} and modified by @Albert221
 * http://www.forumweb.pl/porady-i-tutoriale-www/php-tworzymy-prosty-walidator-oop,82978
 */
class Validator
{
    const DATA = 'data';
    const RULES = 'rules';
    const LABEL = 'label';
    const VALUE = 'value';

    /**
     * @var int Number of data and rules.
     */
    protected $amount = 0;

    /**
     * @var array Data.
     */
    protected $data = [];

    /**
     * @var array Rules.
     */
    protected $rules = [];

    /**
     * @var array Errors.
     */
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param array $input Data to validate.
     */
    public function __construct(array $input = null)
    {
        if (is_array($input)) {
            $this->setupData($input);
        }
    }

    /**
     * Return errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Run rules and validator.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < $this->amount; ++$i) {
            $this->execute($i);
        }
    }

    /**
     * Load single row to validator.
     *
     * @param $label
     * @param $value
     * @param array $rules
     * @return \Basalt\Validator\Validator
     */
    public function importRow($label, $value, array $rules)
    {
        $this->setupData([
            self::DATA => [
                self::LABEL => $label,
                self::VALUE => $value
            ],
            self::RULES => $rules
        ], true);

        return $this;
    }

    /**
     * Run rule on data and push error on failure.
     *
     * @param int $index Index.
     * @return void
     */
    protected function execute($index)
    {
        $data = $this->data[$index];

        foreach ($this->rules[$index] as $rule) {
            $rule = $this->createInstance($rule, $this->getInput($data));

            if (false === $rule->execute()) {
                $this->errors[] = ['label' => $this->getLabel($data), 'error' => $rule->getError()];
            }
        }
    }

    /**
     * Create instance of Rules.
     *
     * @param string $className Class name.
     * @param $value Value.
     * @return \Basalt\Validator\Rules\RuleInterface
     */
    protected function createInstance($className, $value)
    {
        $className = '\\Basalt\\Validator\\Rules\\' . $className;

        return new $className($value);
    }

    /**
     * Prepare data before validator running.
     *
     * @param array $input Input.
     * @param bool $singleRow Is it single row?
     * @return void
     */
    protected function setupData(array $input, $singleRow = false)
    {
        if ($singleRow) {
            $input = [$input];
        }

        foreach ($input as $row) {
            $this->validateRow($row);
            $this->pushData($row);
        }
    }

    /**
     * Push lists with validated data.
     *
     * @param array $row Row to push.
     * @return void
     */
    protected function pushData(array $row)
    {
        $this->data[] = $row[self::DATA];
        $this->rules[] = $row[self::RULES];
        ++$this->amount;
    }

    protected function validateRow(array $row)
    {
        $keys = array_count_values(array_keys($row));
        $defined = array_count_values(array_values([self::DATA, self::RULES]));

        if ($keys !== $defined) {
            throw new \Exception(sprintf('Input array must have two indexes: `%s` and `%s`', self::DATA, self::RULES));
        }

        $values = array_count_values(array_keys($row[self::DATA]));
        $defined = array_count_values(array_values([self::LABEL, self::VALUE]));

        if ($values !== $defined) {
            throw new \Exception(sprintf('Input data array must have two indexed: `%s` and `%s`', self::LABEL, self::VALUE));
        }

        if (false === is_array($row[self::RULES])) {
            throw new \Exception(sprintf('Rules for %s must be an array!', $row[self::DATA][self::LABEL]));
        }
    }

    /**
     * Return name of data.
     *
     * @param array $data Data.
     * @return string
     */
    protected function getLabel(array $data)
    {
        return (string) $data[self::LABEL];
    }

    /**
     * Return data for validator.
     *
     * @param array $data Data.
     * @return mixed
     */
    protected function getInput(array $data)
    {
        return $data[self::VALUE];
    }
}