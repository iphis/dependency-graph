<?php

namespace iphis\DependencyGraph\Either;

use iphis\DependencyGraph\Option\None;
use iphis\DependencyGraph\Option\OptionInterface;
use iphis\DependencyGraph\Option\Some;

class Left implements EitherInterface
{
    private $_value = null;

    /**
     * Value constructor that wraps the value.
     *
     * @param mixed $value - The value to wrap
     */
    public function __construct($value)
    {
        $this->_value = $value;
    }

    /**
     * Returns true.
     *
     * @return bool
     */
    public function isLeft()
    {
        return true;
    }

    /**
     * Returns false.
     *
     * @return bool
     */
    public function isRight()
    {
        return false;
    }

    /**
     * Calls the `$leftCase` with the wrapped value and returns the result.
     *
     * @param callable $leftCase  - Callable for left case
     * @param callable $rightCase - Callable for right case
     *
     * @return mixed - Whatever the ran case returns
     */
    public function fold($leftCase, $rightCase)
    {
        return $leftCase($this->_value);
    }

    /**
     * Returns `$this` imediately.
     *
     * @param callable $mapper - The mapper to ignore
     *
     * @throws \Exception
     *
     * @return EitherInterface - This instance
     */
    public function map($mapper)
    {
        if (!is_callable($mapper)) {
            throw new \Exception("Can't call Left#map with non callable.");
        }

        return $this;
    }

    /**
     * Returns `$this` imediately.
     *
     * @param callable $flatMapper - Flat mapper to ignore
     *
     * @throws \Exception
     *
     * @return EitherInterface - This instance
     */
    public function flatMap($flatMapper)
    {
        if (!is_callable($flatMapper)) {
            throw new \Exception("Can't call Left#flatMap with non callable.");
        }

        return $this;
    }

    /**
     * Returns the left projection of `Left`. So `Some($value)` is returned.
     *
     * @return OptionInterface - The left projection as `Some`
     */
    public function left()
    {
        return new Some($this->_value);
    }

    /**
     * Returns the right projection of `Left`. So `None` is returned.
     *
     * @return OptionInterface - The right projection as `None`
     */
    public function right()
    {
        return new None();
    }

    /**
     * Returns this `Left` as a `Right`.
     *
     * @return EitherInterface - The left transformed to a `Right`
     */
    public function swap()
    {
        return new Right($this->_value);
    }
}
