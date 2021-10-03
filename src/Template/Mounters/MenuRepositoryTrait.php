<?php

/**
 */

namespace Pedreiro\Template\Mounters;

use Illuminate\Support\Str;

/**
 * MenuRepository helper to make table and object form mapping easy.
 */
trait MenuRepositoryTrait
{
    public static function generateMergeMenus($mergeArray, $groupParent = '', $indice, $values): array
    {
        $group = $groupParent;
        if (is_string($indice)) {
            if (! empty($group)) {
                $tempArrayToMerge = [
                    'text' => explode('|', $indice)[0],
                    'group' => $group,
                ];
                if (isset(explode('|', $indice)[1])) {
                    $tempArrayToMerge['order'] = explode('|', $indice)[1];
                }
                $mergeArray = array_merge(
                    $mergeArray,
                    [
                        $tempArrayToMerge,
                    ]
                );
                $group .= '.';
            } else {
                $mergeArray = array_merge($mergeArray, [$indice]);
            }

            $group .= Str::slug(explode('|', $indice)[0], '-');
        }

        return array_merge(
            $mergeArray,
            self::generateValues($group, $indice, $values)
        );
    }


    public static function generateValues($group, $indice, $values)
    {
        if (Menu::isArrayMenu($values, $indice)) {
            if (! empty($group)) {
                if (! isset($values['group'])) {
                    $values['group'] = $group;
                } else {
                    $values['group'] = $group . '.' . $values[$indice]['group'];
                }
            }

            return [$values];
        }
        
        if (self::isArraysFromMenus($values)) {
            if (! empty($group)) {
                foreach ($values as $indice => $value) {
                    if (! isset($value['group'])) {
                        $values[$indice]['group'] = $group;
                    } else {
                        $values[$indice]['group'] = $group . '.' . $values[$indice]['group'];
                    }
                }
            }

            return $values;
        }

        return self::mergeDinamicGroups($values, $group);
    }


    // public static function generateGroup($array)
    // {
    //     if (! isset($values['group'])) {
    //         $values['group'] = $group;
    //     } else {
    //         $values['group'] = $group . '.' . $values[$indice]['group'];
    //     }
    // }



    public static function isArraysFromMenus($arrayMenu): bool
    {
        if (is_string($arrayMenu)) {
            return false;
        }

        if (! is_array($arrayMenu)) {
            return false;
        }

        foreach ($arrayMenu as $indice => $values) {
            if (! Menu::isArrayMenu($values, $indice)) {
                return false;
            }
        }

        return true;
    }
}
