@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    @if(isset($field['value']) && $field['value'])
        <div class="row mb-3">
            @php
                $images = is_array($field['value']) ? $field['value'] : json_decode($field['value'], true);
                if (!is_array($images)) $images = [];
            @endphp
            @foreach($images as $image)
                <div class="col-md-3 mb-3">
                    <div style="position: relative; border-radius: 8px; overflow: hidden; border: 2px solid #e9ecef;">
                        @php
                            $src = filter_var($image, FILTER_VALIDATE_URL) ? $image : asset('storage/' . ltrim($image, '/'));
                        @endphp
                        <img src="{{ $src }}" style="width: 100%; height: 150px; object-fit: cover; display: block;">
                        <div style="position: absolute; top: 8px; right: 8px;">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeImage(this, '{{ $image }}')" style="border-radius: 4px; padding: 4px 8px;">
                                <i class="la la-trash"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="{{ $field['name'] }}[]" value="{{ $image }}">
                </div>
            @endforeach
        </div>
    @endif

    <div>
        <input
            type="file"
            name="{{ $field['name'] }}_temp[]"
            id="{{ $field['name'] }}"
            @include('crud::fields.inc.attributes', ['default_class' => 'form-control'])
            multiple
            accept="image/*"
        >
    </div>

    @if(isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')

<script>
function removeImage(button, imagePath) {
    if (confirm('Are you sure you want to remove this image?')) {
        const container = button.closest('.col-md-3');
        container.remove();
    }
}
</script>
