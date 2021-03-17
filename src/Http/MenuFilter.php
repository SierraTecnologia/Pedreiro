<?php

namespace Pedreiro\Http;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Laratrust;
use Log;
use Request;
use Session;

class MenuFilter implements FilterInterface
{
    public $splitForSection = true;

    public function transform($item)
    {
        if (isset($item['route']) && ! \Route::has($item['route'])) {
            Log::info('Menu não existe: '.$item['route']);

            return false;
        }

        if (isset($item['config']) && ! config($item['config'], false)) {
            Log::info('Menu desabilitado: '.$item['config']);

            return false;
        }



        // if (isset($item['permission']) && ! Laratrust::can($item['permission'])) {
        //     return false;
        // }
        //         if (!isset($item['header']) && $item['text']!=="Dashboard" && $item['text']!=="Visitas" && $item['text']!=="Plugins" && $item['text']!=="Others" )
        // dd($item);
        $user = Auth::user();

        if ($this->splitForSection && !$this->verifySection($item, $user)) {
            return false;
        }
        
        //
        if (\Illuminate\Support\Facades\Config::get('app.env') == 'production' && !$this->verifyLevel($item, $user)) {
            return false;
        }

        if ($this->isInDevelopment($item, $user)) {
            return false;
        }

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

    private function verifySection($item, $user): bool
    {
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
}
