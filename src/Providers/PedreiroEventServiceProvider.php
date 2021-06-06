<?php

namespace Pedreiro\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class PedreiroEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'eloquent.saved: Pedreiro\Models\Blog' => [
        //     'Pedreiro\Models\Blog@afterSaved',
        // ],
        // 'eloquent.saved: Pedreiro\Models\Negocios\Page' => [
        //     'Pedreiro\Models\Negocios\Page@afterSaved',
        // ],
        // 'eloquent.saved: Pedreiro\Models\Event' => [
        //     'Pedreiro\Models\Event@afterSaved',
        // ],
        // 'eloquent.saved: Pedreiro\Models\FAQ' => [
        //     'Pedreiro\Models\FAQ@afterSaved',
        // ],
        // 'eloquent.saved: Pedreiro\Models\Translation' => [
        //     'Pedreiro\Models\Translation@afterSaved',
        // ],
        // 'eloquent.saved: Pedreiro\Models\Widget' => [
        //     'Pedreiro\Models\Widget@afterSaved',
        // ],

        // 'eloquent.created: Pedreiro\Models\Blog' => [
        //     'Pedreiro\Models\Blog@afterCreate',
        // ],
        // 'eloquent.created: Pedreiro\Models\Negocios\Page' => [
        //     'Pedreiro\Models\Negocios\Page@afterCreate',
        // ],
        // 'eloquent.created: Pedreiro\Models\Event' => [
        //     'Pedreiro\Models\Event@afterCreate',
        // ],
        // 'eloquent.created: Pedreiro\Models\FAQ' => [
        //     'Pedreiro\Models\Event@afterCreate',
        // ],
        // 'eloquent.created: Pedreiro\Models\Widget' => [
        //     'Pedreiro\Models\Widget@afterCreate',
        // ],
        // 'eloquent.created: Pedreiro\Models\Link' => [
        //     'Pedreiro\Models\Link@afterCreate',
        // ],

        // 'eloquent.deleting: Pedreiro\Models\Blog' => [
        //     'Pedreiro\Models\Blog@beingDeleted',
        // ],
        // 'eloquent.deleting: Pedreiro\Models\Negocios\Page' => [
        //     'Pedreiro\Models\Negocios\Page@beingDeleted',
        // ],
        // 'eloquent.deleting: Pedreiro\Models\Event' => [
        //     'Pedreiro\Models\Event@beingDeleted',
        // ],
        // 'eloquent.deleting: Pedreiro\Models\FAQ' => [
        //     'Pedreiro\Models\FAQ@beingDeleted',
        // ],
        // 'eloquent.deleting: Pedreiro\Models\Translation' => [
        //     'Pedreiro\Models\Translation@beingDeleted',
        // ],
        // 'eloquent.deleting: Pedreiro\Models\Widget' => [
        //     'Pedreiro\Models\Widget@beingDeleted',
        // ],
    ];

    // /**
    //  * Determine if events and listeners should be automatically discovered.
    //  *
    //  * @return bool
    //  */
    // public function shouldDiscoverEvents()
    // {
    //     return true;
    // }


    /**
     * Register any other events for your application.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function boot()
    {
        parent::boot();
    }
}
