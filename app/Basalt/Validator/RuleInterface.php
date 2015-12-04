<?php

namespace Basalt\Validator;

interface RuleInterface
{
    /**
     * @param mixed $value
     */
    public function __construct($value);

    /**
     * @return bool
     */
    public function execute();

    /**
     * @return string
     */
    public function getRuleName();

    /**
     * @return string
     */
    public function getError();
}
