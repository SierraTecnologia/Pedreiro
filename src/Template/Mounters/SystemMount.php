<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Pedreiro\Template\Mounters;

use Session;
use Translation;
use Cache;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
/**
 * SystemMount helper to make table and object form mapping easy.
 */
class SystemMount
{
    public function __construct()
    {

    }

    /**
     * @return string[]
     *
     * @psalm-return array{0: \Support\SupportServiceProvider::class, 1: \Porteiro\PorteiroProvider::class, 2: \Pedreiro\PedreiroServiceProvider::class, 3: \Informate\InformateProvider::class, 4: \Translation\TranslationProvider::class, 5: \Locaravel\LocaravelProvider::class, 6: \Population\PopulationProvider::class, 7: \Telefonica\TelefonicaProvider::class, 8: \MediaManager\MediaManagerProvider::class, 9: \Stalker\StalkerProvider::class, 10: \Audit\AuditProvider::class, 11: \Tracking\TrackingProvider::class, 12: \Integrations\IntegrationsProvider::class, 13: \Transmissor\TransmissorProvider::class, 14: \Market\MarketProvider::class, 15: \Bancario\BancarioProvider::class, 16: \Operador\OperadorProvider::class, 17: \Fabrica\FabricaProvider::class, 18: \Finder\FinderProvider::class, 19: \Casa\CasaProvider::class, 20: \Trainner\TrainnerProvider::class, 21: \Gamer\GamerProvider::class, 22: \Jogos\JogosProvider::class, 23: \Facilitador\FacilitadorProvider::class, 24: \Boravel\BoravelProvider::class, 25: \Siravel\SiravelProvider::class, 26: \Cms\CmsProvider::class, 27: \PrivateJustice\PrivateJusticeProvider::class, 28: \Legislateiro\LegislateiroProvider::class}
     */
    public static function getProviders(): array
    {
        /**
         * Nao tem Atlassian, Aws, counstris, cudmaster, crypto,
         * tecnico, tramite
         */
        return [
            \Support\SupportServiceProvider::class,
            \Porteiro\PorteiroProvider::class,
            \Pedreiro\PedreiroServiceProvider::class,

            \Informate\InformateProvider::class,
            \Translation\TranslationProvider::class,
            \Locaravel\LocaravelProvider::class,
            \Population\PopulationProvider::class,
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



            \Arquiteto\ArquitetoProvider::class,
            \Cerebro\CerebroProvider::class,
            \Escritor\EscritorProvider::class,
            \SocialEvents\SocialEventsProvider::class,
            \Socrates\SocratesProvider::class,
            \Templeiro\TempleiroProvider::class,
        ];
    }

    /**
     * @return void
     */
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
            // dd($allMenus);
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
