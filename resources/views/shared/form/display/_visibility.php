<?php
// Make help
$help = (!empty($item) && $uri = $item->getUriAttribute()) ?
    __('pedreiro::display.visibility.help', ['uri' => $uri]) :
    __('pedreiro::display.visibility.alternate_help');

// Check if they have permission
if (!app('facilitador.user')->can('publish', $controller)) {
    $status = $item && $item->public ? __('pedreiro::display.visibility.published') : __('pedreiro::display.visibility.draft');
    echo Former::note('Status', $status)->blockHelp($help);
    return;
}

// Render radios
echo Former::radios('public', __('pedreiro::display.visibility.label'))->inline()->radios(
    array(
    __('pedreiro::display.visibility.public') => array('value' => '1', 'checked' => empty($item) ? true : $item->public),
    __('pedreiro::display.visibility.private') => array('value' => '0', 'checked' => empty($item) ? false : !$item->public),
    )
)->blockHelp($help);
