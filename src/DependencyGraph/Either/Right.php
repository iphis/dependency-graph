<?php

namespace iphis\DependencyGraph\Either;

use Exception;
use iphis\DependencyGraph\Option\None;
use iphis\DependencyGraph\Option\OptionInterface;
use iphis\DependencyGraph\Option\Some;

class Right implements EitherInterface
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
     * Returns false.
     *
     * @return bool
     */
    public function isLeft()
    {
        return false;
    }

    /**
     * Returns true.
     *
     * @return bool
     */
    public function isRight()
    {
        return true;
    }

    /**
     * Calls the `$rightCase` with the wrapped value and returns the result.
     *
     * @param callable } $leftCase  - Callable for left case
     * @param callable } $rightCase - Callable for right case
     *
     * @return mixed - Whatever the ran case returns
     */
    public function fold($leftCase, $rightCase)
    {
        return $rightCase($this->_value);
    }

    /**
     * Applies the `$mapper` to the wrapped inner value of this `Right`
     * and returns a new `Right`.
     *
     * @param callable $mapper - The mapper to apply
     *
     * @throws Exception
     *
     * @return EitherInterface - The new `Right` value
     */
    public function map($mapper)
    {
        if (!is_callable($mapper)) {
            throw new Exception("Can't call Right#map with non callable.");
        }

        return new self($mapper($this->_value));
    }

    /**
     * Applies the `$flatMapper` to the wrapped inner value of this `Right`.
     * The flat mapper must return an `Either` type.
     *
     * @param callable $flatMapper - Callable to apply on the inner value
     *
     * @throws Exception
     *
     * @return EitherInterface - The result of the flat map
     */
    public function flatMap($flatMapper)
    {
        if (!is_callable($flatMapper)) {
            throw new Exception("Can't call Right#flatMap with non callable.");
        }
        $flatMapped = $flatMapper($this->_value);
        if (!($flatMapped instanceof EitherInterface)) {
            throw new Exception(
                'Function passed to Right#flatMap must return Either'
            );
        }

        return $flatMapped;
    }

    /**
     * Returns the left projection of `Left`. So `None` is returned.
     *
     * @return OptionInterface - The left projection as `None`
     */
    public function left()
    {
        return new None();
    }

    /**
     * Returns the right projection of `Left`. So `Some($value)` is returned.
     *
     * @return OptionInterface - The right projection as `Some`
     */
    public function right()
    {
        return new Some($this->_value);
    }

    /**
     * Returns this `Right` as a `Left`.
     *
     * @return EitherInterface - The left transformed to a `Left`
     */
    public function swap()
    {
        return new Left($this->_value);
    }
}
