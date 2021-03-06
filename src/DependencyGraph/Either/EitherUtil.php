<?php

namespace iphis\DependencyGraph\Either;

use Exception;
use iphis\DependencyGraph\Map;

/**
 * Class EitherUtil.
 */
class EitherUtil
{
    /**
     * @param $map
     *
     * @return mixed
     */
    public static function lefts($map)
    {
        $map = self::_ensureMap($map);

        return $map->filter(
            function ($value) {
                return $value instanceof Left;
            }
        );
    }

    /**
     * @param $map
     *
     * @return mixed
     */
    public static function rights($map)
    {
        $map = self::_ensureMap($map);

        return $map->filter(
            function ($value) {
                return $value instanceof Right;
            }
        );
    }

    /**
     * @param $map
     *
     * @return mixed
     */
    public static function partition($map)
    {
        $map = self::_ensureMap($map);

        return $map->partition(
            function ($value) {
                return $value instanceof Left;
            }
        );
    }

    /**
     * @param $map
     *
     * @throws \Exception
     *
     * @return Map
     */
    private static function _ensureMap($map)
    {
        if (is_array($map)) {
            return new Map($map);
        }
        if (!($map instanceof Map)) {
            throw new Exception('EitherUtil methods require map or array');
        }

        return $map;
    }
}
