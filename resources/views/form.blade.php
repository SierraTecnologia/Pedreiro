@foreach ($fields as $field)

    <div class="form-group">
        @include( "pedreiro::inputs.{$field['type']}")
    </div>

@endforeach