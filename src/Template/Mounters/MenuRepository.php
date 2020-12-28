<?php

/**
 */

namespace Pedreiro\Template\Mounters;

use Exception;
use Illuminate\Support\Collection;

/**
 * MenuRepository helper to make table and object form mapping easy.
 */
class MenuRepository
{
    use MenuRepositoryTrait;

    protected $menus = [];


    public function __construct($menus = [])
    {
        $mergeByCode = [];
        foreach ($menus as $menu) {
            if ($menu) {
                if (! isset($mergeByCode[$menu->getCode()])) {
                    $mergeByCode[$menu->getCode()] = $menu;
                } else {
                    $mergeByCode[$menu->getCode()]->mergeWithMenu($menu);
                }
            }
        }

        $this->menus = $this->getInOrder(
            array_values($mergeByCode)
        );
        // dd(

        //     $this->menus
        // // return $this->getInOrder($menuArrayList);
        // );
    }


    public static function createFromArray(array $array): MenuRepository
    {
        $arrayFromMenuEntities = [];
        foreach ($array as $value) {
            if ($createMenuArray = Menu::createFromArray($value)) {
                $arrayFromMenuEntities[] = $createMenuArray;
            }
        }

        return new self($arrayFromMenuEntities);
    }

    public static function createFromMultiplosArray(Collection $array): MenuRepository
    {
        $mergeArray = [];

        if (! self::isArraysFromMenus($array) && ! empty($array)) {
            foreach ($array as $value) {
                $mergeArray = array_merge($mergeArray, self::mergeDinamicGroups($value));
            }
        }

        return self::createFromArray($mergeArray);
    }


    private static function mergeDinamicGroups($array, $groupParent = '')
    {
        $mergeArray = [];

        if (self::isArraysFromMenus($array)) {
            return $array;
        }
        
        if (! is_array($array)) {
            throw new Exception('Deveria ser um array aqui no mergeDinamicGroups do MenuRepository');
        };


        foreach ($array as $indice => $values) {
            $mergeArray = self::generateMergeMenus(
                $mergeArray,
                $groupParent,
                $indice,
                $values
            );
        }

        return $mergeArray;
    }
    
    public function getTreeInArray($parent = 'root')
    {
        $menuArrayList = [];

        $byGroup = $this->groupBy('group');

        if (isset($byGroup[$parent])) {
            foreach ($byGroup[$parent] as $menu) {
                $menuArray = $menu->toArray();
                if (! empty($byGroup[$menu->getAddressSlugGroup()])) {
                    if (is_string($menuArray)) {
                        $menuArrayList[] = $menuArray;
                        $menuArray = $this->getTreeInArray($menu->getAddressSlugGroup());
                    } else {
                        $menuArray['submenu'] = $this->getTreeInArray($menu->getAddressSlugGroup());
                    }
                }
                if (Menu::isArrayMenu($menuArray)) {
                    $menuArrayList[] = $menuArray;
                } else {
                    $menuArrayList = array_merge($menuArrayList, $menuArray);
                }
            }
        }

        return $this->getInOrder($menuArrayList);
    }

    private function getInOrder($arrayMenu)
    {
        if (is_object($arrayMenu[0])) {
            usort(
                $arrayMenu,
                function ($a, $b) {
                    return $a->getOrder() > $b->getOrder();
                }
            );

            return $arrayMenu;
        }


        $columns = array_column($arrayMenu, 'order');
        if (count($columns) == count($arrayMenu)) {
            array_multisort($columns, SORT_ASC, $arrayMenu);
        }

        return $arrayMenu;
    }

    private function groupBy($attribute)
    {
        $byGroup = [];
        $getFunction = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));

        foreach ($this->menus as $menu) {
            if (! isset($byGroup[$menu->{$getFunction}()])) {
                $byGroup[$menu->{$getFunction}()] = [];
            }
            $byGroup[$menu->{$getFunction}()][] = $menu;
        }

        // dd($byGroup, $this->menus);
        return $byGroup;
    }
}
