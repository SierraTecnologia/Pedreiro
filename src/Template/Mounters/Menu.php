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

    public static function isArrayMenu($arrayMenu, $indice = false)
    {
        if (is_string($arrayMenu) && ! is_string($indice)) {
            return true;
        }

        return isset($arrayMenu['text']) || isset($arrayMenu['key']);
    }

    public function attributeIsDefault($attribute)
    {
        return is_null($this->$attribute);
    }

    public function mergeWithMenu(Menu $menu)
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

    public function getAttributes()
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
     *
     */
    public function getAddressSlugGroup()
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
    public function setKey($value)
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
    public function setCode($value)
    {
        $this->code = $value;
    }

    public function getSlug()
    {
        return $this->slug;
    }
    public function setSlug($value)
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
    public function setText($value)
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
    public function setRoute($value)
    {
        $this->route = $value;
    }

    public function getUrl()
    {
        return $this->url;
    }
    public function setUrl($value)
    {
        $this->url = $value;
    }

    public function getIcon()
    {
        return $this->icon;
    }
    public function setIcon($value)
    {
        $this->icon = $value;
    }

    public function getLabelColor()
    {
        return $this->label_color;
    }
    public function setLabelColor($value)
    {
        $this->label_color = $value;
    }

    public function getIconColor()
    {
        return $this->icon_color;
    }
    public function setIconColor($value)
    {
        $this->icon_color = $value;
    }

    public function getNivel()
    {
        return $this->nivel;
    }
    public function setNivel($value)
    {
        $this->nivel = $value;
    }

    public function getLevel()
    {
        return $this->level;
    }
    public function setLevel($value)
    {
        $this->level = $value;
    }

    public function getFeature()
    {
        return $this->feature;
    }
    public function setFeature($value)
    {
        $this->feature = $value;
    }

    public function getSpace()
    {
        return $this->space;
    }
    public function setSpace($value)
    {
        $this->space = $value;
    }

    public function getSection()
    {
        return $this->section;
    }
    public function setSection($value)
    {
        $this->section = $value;
    }

    public function getDontSection()
    {
        return $this->dontSection;
    }
    public function setDontSection($value)
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
    public function setOrder($value)
    {
        $this->order = $value;
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function setConfig($value)
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
    public function setGroup($value)
    {
        $value = explode('|', $value);
        $this->group = $value[0];
    }
    public function getError()
    {
        return $this->error;
    }
    public function setError($value)
    {
        $this->error = $value;
    }
    public function getTopnav()
    {
        return $this->topnav;
    }
    public function setTopnav($value)
    {
        $this->topnav = $value;
    }
    public function getTopnavUser()
    {
        return $this->topnav_user;
    }
    public function setTopnavUser($value)
    {
        $this->topnav_user = $value;
    }
    public function getTopnavRight()
    {
        return $this->topnav_right;
    }
    public function setTopnavRight($value)
    {
        $this->topnav_right = $value;
    }
    public function getData()
    {
        return $this->data;
    }
    public function setData($value)
    {
        $this->data = $value;
    }
    public function getActive()
    {
        return $this->active;
    }
    public function setActive($value)
    {
        $this->active = $value;
    }

    public function getDevStatus(): int
    {
        return $this->dev_status;
    }
    public function setDevStatus(int $value)
    {
        $this->dev_status = $value;
    }

    public function getDivisory(): bool
    {
        return $this->isDivisory;
    }
    public function setDivisory(bool $value)
    {
        $this->isDivisory = $value;
    }
    
    /**
     * Caso nao seja pra exibir, cria log e retorna false.
     *
     * Se nao retorna a propria instancia
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
     */
    protected function isToDisplay()
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
