<?php

namespace iphis\DependencyGraph\Either;

interface EitherInterface
{
    /**
     * Returns true if this `Either` type is `Left`, false otherwise.
     *
     * @return bool
     */
    public function isLeft();

    /**
     * Returns true if this `Either` type is `Right`, false otherwise.
     *
     * @return bool
     */
    public function isRight();

    /**
     * This function takes two callable types as it's arguments and it will
     * only call one of them. If this `Either` type is `Left` it calls
     * `$leftCase` with the left value. If this is the `Right` type it calls
     * the `$rightCase` with the right value.
     *
     * @param callable $leftCase  - Callable for left case
     * @param callable $rightCase - Callable for right case
     *
     * @return mixed - Whatever the ran case returns
     */
    public function fold($leftCase, $rightCase);

    /**
     * Given a `mapper` function this function applies the mapper to the
     * inner wrapped value if and only if this `Either` type is `Right`.
     * If this `Either` type is `Left` then this function returns `$this`
     * immediately without applying the mapper.
     *
     * @param callable $mapper - The mapper to apply
     *
     * @return EitherInterface - The new `Either` type
     */
    public function map($mapper);

    /**
     * Given a `flatMapper` function, this function applies the flat mapper
     * to the wrapped value of the `Either` type if the either type is `Right`.
     * If the `Either` type is `Left` this function immediately returns `$this`.
     *
     * @param callable $flatMapper - The flat mapper to apply
     *
     * @return EitherInterface - The new `Either` type
     */
    public function flatMap($flatMapper);

    /**
     * Returns an `Option` projection of the `Left` value of this `Either` type.
     * So if this is type `Left` it returns `Some($value)` but if this is
     * `Right` it returns `None`.
     *
     * @return \iphis\DependencyGraph\Option\OptionInterface
     */
    public function left();

    /**
     * Returns an `Option` projection of the `Right` value of this `Either`
     * type. So if this is type `Right` it returns `Some($value)` but if this is
     * `Left` it returns `None`.
     *
     * @return \iphis\DependencyGraph\Option\OptionInterface
     */
    public function right();

    /**
     * Returns the `Either` type as the opposite side. `Left` returns `Right`
     * and vice versa.
     *
     * @return EitherInterface
     */
    public function swap();
}
