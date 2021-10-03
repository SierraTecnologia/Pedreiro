<?php

namespace Pedreiro\Http;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;
use Log;
use Request;
use Session;
use Illuminate\Support\Str;

class MenuFilter implements FilterInterface
{
    public $splitForSection = true;

    /**
     * @return array|false
     */
    public function transform($item)
    {
        // Para debug
        // if (isset($item['text']) && $item['text'] == 'Integrações') {
        //     dd($item);
        //     return false;
        // }


        if (isset($item['route']) && ! \Route::has($item['route'])) {
            Log::debug('Menu não existe: '.$item['route']);

            return false;
        }

        if (isset($item['config']) && ! config($item['config'], false)) {
            Log::debug('Menu desabilitado: '.$item['config']);

            return false;
        }

        // Remove Itens Sem Filhos
        if (
            (!isset($item["submenu"]) || empty($item["submenu"]))
            && isset($item["text"]) && 
            (!isset($item["href"]) || $item["href"]=='#')
        ) {
            Log::debug('Sem filho, tirando fora: '.$item['text']);
            return false;
        }

        // if (isset($item['permission']) && ! Laratrust::can($item['permission'])) {
        //     return false;
        // }
        $user = Auth::user();

        if (!$this->verifySection($item, $user)) {
            return false;
        }
        
        // //
        // if (! $this->verifyLevel($item, $user)) {
        //     Log::debug('Sem level, tirando fora: '.$item['text']);
        //     return false;
        // }

        // //
        // if (!$this->hasPermissionForUser($item, $user)) {
        //     Log::debug('Sem permission, tirando fora: '.$item['text']);
        //     return false;
        // }

        // if ($this->isInDevelopment($item, $user)) {
        //     Log::debug('Is in Development: '.$item['text']);
        //     return false;
        // }

        // if (!$this->verifySpace($item, $user)) {
        //     return false;
        // }

        // Translate
        if (isset($item["text"])) {
            $item["text"] = _t($item["text"]);
        }
        if (isset($item["header"])) {
            $item["header"] = _t($item["header"]);
        }

        return $item;
    }

    /**
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     */
    private function verifySection(array $item, ?\Illuminate\Contracts\Auth\Authenticatable $user): bool
    {         
        // Se nao for pra dividir entre as sessões, então nao remove o menu, return true
        if (!$this->splitForSection || !config('siravel.habilityTopNav', true)) {
            return true;
        }

        $actualSection = Request::segment(1);
        $section = null;
        if (isset($item['section']) && $actualSection !== $item['section']) {
            return false;
        }

        if (isset($item['dontSection']) && $actualSection === $item['dontSection']) {
            return false;
        }

        return true;
    }

    private function isInDevelopment($item, $user): bool
    {
        if (isset($item['dev_status']) && $item['dev_status'] == 0) {
            return true;
        }
        if (isset($item['dev_status']) && $item['dev_status'] == 2 && \Illuminate\Support\Facades\Config::get('app.env') == 'production') {
            return true;
        }

        return false;
    }

    private function verifySpace($item, $user): bool
    {
        $space = null;
        if (isset($item['space'])) {
            $space = $item['space'];
        }

        if (empty($space)) {
            return true;
        }

        return $space == app('support.router')->getRouteSpace(); //Session::get('space');
    }

    private function verifyLevel($item, $user): bool
    {
        $level = 0;
        if (isset($item['level'])) {
            $level = (int) $item['level'];
        }

        // Possui level inteiro e usuario nao logado
        if ($level > 0 && ! $user) {
            return false;
        }
        if ($level <= 0) {
            return true;
        }

        if (! $user || $level > $user->getLevelForAcessInBusiness()) {
            return false;
        }

        return true;
    }

    private function hasPermissionForUser($item, $user): bool
    {
        // Retira usuarios que nao tem acesso
        if (isset($item['section']) && (!$user || !$user->hasAccessTo($item['section']))) {
            return false;
        }

        // Verifica as outras urls
        $permissionsByUrl = [
            'isRoot' => [
                'rica',
                'root'
            ],
            'isAdmin' => [
                'admin',
            ]
        ];
        
        $actualSection = Request::segment(1);
        foreach ($permissionsByUrl as $permission => $values) {
            if (isset($item['route'])) {
                if (Str::startsWith($item['route'], $values)) {
                    return $user->{$permission}();
                }
            }
            if (isset($item['url'])) {
                if (Str::contains($item['url'], $values)) {
                    return $user->{$permission}();
                }
            }
        }
        return true;
    }

}
