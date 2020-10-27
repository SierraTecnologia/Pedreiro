<?php

namespace Pedreiro\Http\Controllers\Admin;

use Pedreiro\Services\VersionService;

class PageController extends Controller
{
    /**
     * The controller class name. Ex: Admin\PostsController
     *
     * @var string
     */
    public $controller;

    /**
     * The HTML title, shown in header of the vie. Ex: News Posts
     *
     * @var string
     */
    public $title = 'Paginas';

    /**
     * The text description of what this controller manages, shown in the header.
     * Ex: "Relevant news about the brand"
     *
     * @var string
     */
    public $description;


    public $subTitle = false;

    public function title($controller_name = null)
    {
        return $this->subTitle;
    }

    public function help()
    {
        $this->subTitle = 'Ajuda';

        return view('pedreiro::pages.help');
    }

    public function changelog(VersionService $versionService)
    {
        $this->subTitle = trans('words.changelog');
        $releases = $versionService->getReleases();

        return view('pedreiro::pages.releases', compact('releases'));
    }
}
