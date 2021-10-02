<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Pedreiro\Template\Mounters;

use Session;
use Translation;

/**
 * SystemMount helper to make table and object form mapping easy.
 */
class SystemMount
{
    public function __construct()
    {
        
    }

    public static function getProviders()
    {
        return [
            \Support\SupportServiceProvider::class,
            \Porteiro\PorteiroProvider::class,
            \Pedreiro\PedreiroServiceProvider::class,
            
            \Informate\InformateProvider::class,
            \Translation\TranslationProvider::class,
            \Locaravel\LocaravelProvider::class,
            \Populate\PopulateProvider::class,
            \Telefonica\TelefonicaProvider::class,
            \MediaManager\MediaManagerProvider::class,
            \Stalker\StalkerProvider::class,
            \Audit\AuditProvider::class,
            \Tracking\TrackingProvider::class,

            \Integrations\IntegrationsProvider::class,
            \Transmissor\TransmissorProvider::class,
            \Market\MarketProvider::class,
            \Bancario\BancarioProvider::class,
            \Operador\OperadorProvider::class,
            \Fabrica\FabricaProvider::class,
            \Finder\FinderProvider::class,
            \Casa\CasaProvider::class,

            \Trainner\TrainnerProvider::class,
            \Gamer\GamerProvider::class,
            \Jogos\JogosProvider::class,
            
            \Facilitador\FacilitadorProvider::class,
            \Boravel\BoravelProvider::class,
            \Siravel\SiravelProvider::class,
            \Cms\CmsProvider::class,
            \PrivateJustice\PrivateJusticeProvider::class,

            \Legislateiro\LegislateiroProvider::class,
        ];
    }

    public function loadMenuForAdminlte($event)
    {

        if (! config('siravel.packagesMenu', false)) {
            return ;
        }

        if (Session::get('original_user')) {
            $event->menu->add(
                [
                'text' => 'Return to your Login',
                'url' => '/users/switch-back',
                ]
            );
        }

        $allMenus = Cache::rememberForever('system-mount-load-menu-for-adminlte', function () {
            return collect($this->getAllMenus()->getTreeInArray());
        });

        // dd($this->getAllMenus(), $this->getAllMenus()->getTreeInArray());
        // $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
        $allMenus->map(
            function ($valor) use ($event) {
                $event->menu->add($valor);
            }
        );
        // });
    }

    public function loadMenuForArray()
    {
        return Cache::rememberForever('system-mount-load-menu-for-array', function () {
            return collect($this->getAllMenus()->getTreeInArray())->map(
                function ($valor) {
                    return $valor;
                }
            )->values()->all();
        });
    }

    protected function getAllMenus()
    {
        return Cache::rememberForever('system-mount-get-all-menus', function () {
            return MenuRepository::createFromMultiplosArray(
                collect(
                    self::getProviders()
                )->reject(
                    function ($class) {
                        return ! class_exists($class) || ! is_array($class::$menuItens) || empty($class::$menuItens);
                    }
                )->map(
                    function ($class) {
                        return $class::$menuItens;
                    }
                ) //->push(Translation::menuBuilder())
            );
        });
    }
}
