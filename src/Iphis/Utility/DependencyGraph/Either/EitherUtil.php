<?php
namespace Iphis\Utility\DependencyGraph\Either;

use Exception;
use Iphis\Utility\DependencyGraph\Map;

/**
 * Class EitherUtil
 *
 * @package PlasmaConduit\either
 */
class EitherUtil
{
    /**
     * @param $map
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
     * @return Map
     * @throws \Exception
     */
    private static function _ensureMap($map)
    {
        if (is_array($map)) {
            return new Map($map);
        }
        if (!($map instanceof Map)) {
            throw new Exception("EitherUtil methods require map or array");
        }

        return $map;
    }
}