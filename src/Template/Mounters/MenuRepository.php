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
            // dd($array);
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

    /**
     * @return (mixed|object|string)[]
     *
     * @psalm-return array<mixed|object|string>
     */
    public function getTreeInArray($parent = 'root', $order = null): array
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

    /**
     * @param (mixed|string)[] $arrayMenu
     *
     * @return (mixed|object|string)[]
     *
     * @psalm-return array<mixed|object|string>
     */
    private function getInOrder(array $arrayMenu): array
    {
        if (is_object($arrayMenu[0])) {
            usort(
                $arrayMenu,
                function ($a, $b) {
                    // usort(): Returning bool from comparison function is deprecated, return an integer less than, equal
                    // Invez de retornar bool, retorna 1, -1, ou 0
                    if ($a->getOrder() > $b->getOrder()) {
                        return 1;
                    } else {
                        return -1;
                    }
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

    /**
     * @param string $attribute
     *
     * @return array[]
     *
     * @psalm-return array<list<mixed>>
     */
    private function groupBy(string $attribute): array
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
