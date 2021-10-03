<?php

/**
 * Serviço referente a linha no banco de dados
 */

namespace Pedreiro\Template\Mounters;

use Illuminate\Support\Str;
use Log;
use Route;
use Auth;

/**
 * Menu helper to make table and object form mapping easy.
 */
class Menu
{
    protected $key = null;
    protected $code = null;
    protected $slug = null;

    protected $text = null;
    protected $icon = null;
    protected $icon_color = null;
    protected $label_color = null;
    protected $level = null;
    protected $nivel = null;

    protected $url = null;
    protected $route = null;

    /**
     * Somente ira aparecer para as features selecionadas
     */
    protected $feature = null;

    protected $space = null;
    protected $section = null;
    protected $dontSection = null;
    
    /**
     *
     */
    protected $group = null;
    protected $order = null;

    // @todo nao lembro desse
    protected $config = null;


    /**
     *
     */
    protected $isDivisory = false;

    protected $error = null;

    protected $topnav = false;
    protected $topnav_user = false;
    protected $topnav_right = false;
    protected $data = null;
    protected $active = null;



    /**
     * 0 -> Desabilitado
     * 1 -> Habilitado
     * 2 -> Em Desenvolvimento
     */
    protected $dev_status = 1;

    /**
     *  'text'    => 'Finder',
     * 'icon'    => 'cog',
     * 'nivel' => \Porteiro\Models\Role::$GOOD,
     * 'submenu' => \Finder\Services\MenuService::getAdminMenu(),
     *
     * @return false|static
     */
    public static function createFromArray($item)
    {
        $instance = new Menu;


        // Caso seja uma divisoria String sempre é
        // Ps: Caso habilityTopNav desativado entao nao mostra as divisorias
        if (is_string($item)) {
            if (!config('siravel.habilityTopNav', true)) {
                Log::debug('habilityTopNav Ativado removendo o menu: ' . $item);
                return false;
            }
            $instance->isDivisory = true;
            $item = explode('|', $item);
            $instance->setText($item[0]);
            if (! empty($item[1])) {
                $instance->setOrder($item[1]);
            }
            return $instance->validateAndReturn();
        }

        // Personalizacao de Config
        if (!config('siravel.habilityTopNav', true) && isset($item['topnav']) && $item['topnav']!==false) {            
            $item['topnav'] = false;
            $item['divisory'] = true;
            if (isset($item['url'])) {
                $item['section'] = $item['url'];
            }
            if (Auth::user() && !Auth::user()->hasAccessTo($item['section'])) {
                return false;
            }
        } 

        // Caso seja um menu normal, nao divis
        foreach ($item as $attribute => $valor) {
            $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
            $instance->{$methodName}($valor);
        }

        return $instance->validateAndReturn();
    }

    /**
     * @param (int|string)|false $indice
     */
    public static function isArrayMenu($arrayMenu, $indice = false): bool
    {
        if (is_string($arrayMenu) && ! is_string($indice)) {
            return true;
        }

        return isset($arrayMenu['text']) || isset($arrayMenu['key']);
    }

    public function attributeIsDefault(string $attribute): bool
    {
        return is_null($this->$attribute);
    }

    /**
     * @return static
     */
    public function mergeWithMenu(Menu $menu): self
    {
        $divisory = $this->isDivisory;

        foreach ($this->getAttributes() as $attribute) {
            if ($this->attributeIsDefault($attribute) && ! $menu->attributeIsDefault($attribute)) {
                $methodName = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $getMethodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $this->{$methodName}(
                    $menu->{$getMethodName}()
                );
                // $this->isDivisory = false;
            }
        }
        // if () {

        // }

        return $this;
    }

    public function toArray()
    {
        $array = [];

        if ($this->isDivisory) {
            return $this->getText();
        }

        foreach ($this->getAttributes() as $attribute) {
            if (! $this->attributeIsDefault($attribute)) {
                $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));
                $array[$attribute] = $this->{$methodName}();
            }
        }

        return $array;
    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: 'key', 1: 'code', 2: 'slug', 3: 'text', 4: 'url', 5: 'route', 6: 'icon', 7: 'label_color', 8: 'icon_color', 9: 'nivel', 10: 'level', 11: 'feature', 12: 'space', 13: 'section', 14: 'dontSection', 15: 'order', 16: 'config', 17: 'topnav', 18: 'topnav_user', 19: 'topnav_right', 20: 'data', 21: 'active', 22: 'dev_status'}
     */
    public function getAttributes(): array
    {
        return [
            'key',
            'code',

            'slug',
            'text',

            'url',
            'route',

            'icon',
            'label_color',
            'icon_color',

            'nivel',
            'level',
            'feature',
            'space',
            'section',
            'dontSection',
            'order',

            'config',

            'topnav',
            'topnav_user',
            'topnav_right',
            'data',
            'active',

            'dev_status',
        ];
    }


    /**
     * @return string
     */
    public function getAddressSlugGroup(): string
    {
        $group = '';

        if (! $this->attributeIsDefault('group')) {
            $group = $this->getGroup() . '.';
        }

        return $group . $this->getKey();
    }


    public function getKey()
    {
        return $this->key;
    }
    public function setKey(string $value): void
    {
        $value = Str::slug($value, '-');
        $this->key = $value;
    }

    public function getCode()
    {
        if ($this->attributeIsDefault('code')) {
            return $this->getAddressSlugGroup();
        }

        return $this->code;
    }
    public function setCode($value): void
    {
        $this->code = $value;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug(string $value): void
    {
        $value = Str::slug($value, '-');
        if (is_null($this->getKey())) {
            $this->setKey($value);
        }
        $this->slug = $value;
    }

    public function getText()
    {
        return $this->text;
    }
    public function setText(string $value): void
    {
        if (is_null($this->getSlug())) {
            $this->setSlug($value);
        }
        $this->text = $value;
    }

    public function getRoute()
    {
        return $this->route;
    }
    public function setRoute($value): void
    {
        $this->route = $value;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($value): void
    {
        $this->url = $value;
    }

    public function getIcon()
    {
        return $this->icon;
    }
    public function setIcon($value): void
    {
        $this->icon = $value;
    }

    public function getLabelColor()
    {
        return $this->label_color;
    }
    public function setLabelColor($value): void
    {
        $this->label_color = $value;
    }

    public function getIconColor()
    {
        return $this->icon_color;
    }
    public function setIconColor($value): void
    {
        $this->icon_color = $value;
    }

    public function getNivel()
    {
        return $this->nivel;
    }
    public function setNivel($value): void
    {
        $this->nivel = $value;
    }

    public function getLevel()
    {
        return $this->level;
    }
    public function setLevel($value): void
    {
        $this->level = $value;
    }

    public function getFeature()
    {
        return $this->feature;
    }
    public function setFeature($value): void
    {
        $this->feature = $value;
    }

    public function getSpace()
    {
        return $this->space;
    }
    public function setSpace($value): void
    {
        $this->space = $value;
    }

    public function getSection()
    {
        return $this->section;
    }
    public function setSection($value): void
    {
        $this->section = $value;
    }

    public function getDontSection()
    {
        return $this->dontSection;
    }
    public function setDontSection($value): void
    {
        $this->dontSection = $value;
    }

    public function getOrder()
    {
        if (is_null($this->order) || empty($this->order)) {
            return 100;
        }

        return $this->order;
    }
    public function setOrder(string $value): void
    {
        $this->order = $value;
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function setConfig($value): void
    {
        $this->config = $value;
    }

    public function getGroup()
    {
        if (is_null($this->group) || empty($this->group)) {
            return 'root';
        }

        return $this->group;
    }
    public function setGroup($value): void
    {
        $value = explode('|', $value);
        $this->group = $value[0];
    }
    public function getError()
    {
        return $this->error;
    }
    public function setError(string $value): void
    {
        $this->error = $value;
    }
    public function getTopnav()
    {
        return $this->topnav;
    }
    public function setTopnav($value): void
    {
        $this->topnav = $value;
    }
    public function getTopnavUser()
    {
        return $this->topnav_user;
    }
    public function setTopnavUser($value): void
    {
        $this->topnav_user = $value;
    }
    public function getTopnavRight()
    {
        return $this->topnav_right;
    }
    public function setTopnavRight($value): void
    {
        $this->topnav_right = $value;
    }
    public function getData()
    {
        return $this->data;
    }
    public function setData($value): void
    {
        $this->data = $value;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function setActive($value): void
    {
        $this->active = $value;
    }

    public function getDevStatus(): int
    {
        return $this->dev_status;
    }
    public function setDevStatus(int $value): void
    {
        $this->dev_status = $value;
    }

    public function getDivisory(): bool
    {
        return $this->isDivisory;
    }
    public function setDivisory(bool $value): void
    {
        $this->isDivisory = $value;
    }
    
    /**
     * Caso nao seja pra exibir, cria log e retorna false.
     *
     * Se nao retorna a propria instancia
     *
     * @return false|static
     */
    public function validateAndReturn()
    {
        if (! $this->isToDisplay()) {
            Log::info('Menu desabilitado: ' . $this->getError());

            return false;
        }

        return $this;
    }


    /**
     * Protected
     *
     * @return bool
     */
    protected function isToDisplay(): bool
    {
        // Verify Route Exist
        if (! empty($this->getRoute()) && ! Route::has($this->getRoute())) {
            $this->setError(
                'Rota ' . $this->getRoute() . ' não existe!'
            );

            return false;
        }

        return true;
    }
}
