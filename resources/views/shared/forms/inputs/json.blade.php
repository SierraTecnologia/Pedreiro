@include('pedreiro::shared.forms.inputs.label')
<?php /** @todo fazer isso aqui nao funciona */ ?>
<input type="text"
       id="{{ $field['name'] }}"
       class="form-control"
       name="{{ $field['name'] }}"
       value="{{ json_encode(old($field['name'])) ?: json_encode($entity->{$field['name']}) }}"
>