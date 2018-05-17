<?php

namespace collections;

/**
 * Returns an associative array where the keys are values of $key.
 *
 * Based on {@author Chauncey McAskill}'s {@link https://gist.github.com/mcaskill/baaee44487653e1afc0d array_group_by()}
 * function.
 *
 ** __::groupBy([
 **         ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'School bus'],
 **         ['state' => 'CA', 'city' => 'San Diego', 'object' => 'Light bulb'],
 **         ['state' => 'CA', 'city' => 'Mountain View', 'object' => 'Space pen'],
 **     ],
 **     'state'
 ** );
 ** // >> [
 ** //   'IN' => [
 ** //      ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'School bus'],
 ** //      ['state' => 'CA', 'city' => 'San Diego', 'object' => 'Light bulb'],
 ** //   ],
 ** //   'CA' => [
 ** //      ['state' => 'CA', 'city' => 'Mountain View', 'object' => 'Space pen']
 ** //   ]
 ** // ]
 *
 *
 ** __::groupBy([
 **         ['object' => 'School bus', 'metadata' => ['state' => 'IN', 'city' => 'Indianapolis']],
 **         ['object' => 'Manhole', 'metadata' => ['state' => 'IN', 'city' => 'Indianapolis']],
 **         ['object' => 'Basketball', 'metadata' => ['state' => 'IN', 'city' => 'Plainfield']],
 **         ['object' => 'Light bulb', 'metadata' => ['state' => 'CA', 'city' => 'San Diego']],
 **         ['object' => 'Space pen', 'metadata' => ['state' => 'CA', 'city' => 'Mountain View']],
 **     ],
 **     'metadata.state'
 ** );
 ** // >> [
 ** //   'IN' => [
 ** //     'Indianapolis' => [
 ** //       ['object' => 'School bus', 'metadata' => ['state' => 'IN', 'city' => 'Indianapolis']],
 ** //       ['object' => 'Manhole', 'metadata' => ['state' => 'IN', 'city' => 'Indianapolis']],
 ** //     ],
 ** //     'Plainfield' => [
 ** //       ['object' => 'Basketball', 'metadata' => ['state' => 'IN', 'city' => 'Plainfield']],
 ** //     ],
 ** //   ],
 ** //   'CA' => [
 ** //     'San Diego' => [
 ** //       ['object' => 'Light bulb', 'metadata' => ['state' => 'CA', 'city' => 'San Diego']],
 ** //     ],
 ** //     'Mountain View' => [
 ** //       ['object' => 'Space pen', 'metadata' => ['state' => 'CA', 'city' => 'Mountain View']],
 ** //     ],
 ** //   ],
 ** // ]
 *
 *
 ** __::groupBy([
 **         ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'School bus'],
 **         ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'Manhole'],
 **         ['state' => 'CA', 'city' => 'San Diego', 'object' => 'Light bulb'],
 **     ],
 **     function ($value) {
 **         return $value->city;
 **     }
 ** );
 ** // >> [
 ** //   'Indianapolis' => [
 ** //      ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'School bus'],
 ** //      ['state' => 'IN', 'city' => 'Indianapolis', 'object' => 'Manhole'],
 ** //   ],
 ** //   'San Diego' => [
 ** //      ['state' => 'CA', 'city' => 'San Diego', 'object' => 'Light bulb'],
 ** //   ]
 ** // ]
 *
 * @param array $array
 * @param int|float|string|\Closure $key
 *
 * @return array
 *
 */
function groupBy(array $array, $key)
{
    if (!\is_bool($key) && !\is_scalar($key) && !\is_callable($key)) {
        return $array;
    }

    $grouped = [];
    foreach ($array as $value) {
        $groupKey = null;

        if (\is_callable($key)) {
            $groupKey = call_user_func($key, $value);
        } else {
            $groupKey = \__::get($value, $key);
        }

        if ($groupKey === null) {
            continue;
        }

        $grouped[$groupKey][] = $value;
    }

    if (($argCnt = func_num_args()) > 2) {
        $args = func_get_args();

        foreach ($grouped as $_key => $value) {
            $params = array_merge([$value], array_slice($args, 2, $argCnt));
            $grouped[$_key] = call_user_func_array('__::groupBy', $params);
        }
    }

    return $grouped;
}
