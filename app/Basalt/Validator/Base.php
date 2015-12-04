<?php

namespace Basalt\Validator;

class Base
{
    /**
     * @var mixed Data to validate.
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param mixed $value Data to validate.
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return rule name.
     *
     * @param bool $fullName Should it return name with namespace?
     * @return string
     */
    public function getRuleName($fullName = true)
    {
        $reflection = new \ReflectionClass(get_called_class());

        return $fullName ? $reflection->getName() : $reflection->getShortName();
    }

    /**
     * Return error message.
     *
     * @return string
     */
    public function getError()
    {
        return sprintf('validator.%s', $this->getRuleName(false));
    }
}
