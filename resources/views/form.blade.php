@foreach ($fields as $field)

    <div class="form-group">
        @include( "pedreiro::shared.forms.inputs.{$field['type']}")
    </div>

@endforeach