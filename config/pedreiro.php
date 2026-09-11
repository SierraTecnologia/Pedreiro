<?php


return [
    /*
     * The default base layout that the views extend.
     * Change this to your app's main layout or the layout you want to use for the forms.
     */
    'blade_layout' => 'layouts.app',
    // 'blade_layout' => 'pedreiro::layout',

    /*
     * The default section in the base blade layout, that the CRUD views will use.
     */
    'blade_section' => 'content',

    /*
     * Dashboard targets used by the shared browser error pages. Add new
     * application segments here as they are introduced.
     */
    'error_pages' => [
        'section_dashboard_urls' => [
            'admin' => 'admin',
            'master' => 'master',
            'painel' => 'painel',
            'rica' => 'rica',
        ],
    ],

    /*
     * Whether the action buttons (show, edit, delete) of the index view should use font awesome icons.
     * If true, you will have to load the font-awesome css in the base layout.
     * (eg https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css).
     */
    'button_icons' => true,

];
