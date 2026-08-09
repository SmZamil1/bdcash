
@props([
    'type' => null,
    'image' => null,
    'imagePath' => null,
    'size' => null,
    'name' => 'image',
    'id' => 'image-upload-input1',
    'accept' => '.png, .jpg, .jpeg',
    'required' => true,
    'darkMode' => false,
    'showInfo' => true,
])
@php
    $size = $size ?? getFileSize($type);
    $imagePath = $imagePath ?? getImage(getFilePath($type) . '/' . $image, $size);
@endphp
<div class="image-upload">
    <div class="thumb">
        <div class="avatar-preview">
            <div class="profilePicPreview" id="{{ $id }}Preview"
                 style="background-image: url({{ $imagePath }}); 
                        height: 250px;  /* adjust to show full image */
                        width: 100%; 
                        background-size: contain; 
                        background-repeat: no-repeat; 
                        background-position: center;">

                @if($image)
                    <button type="button" class="remove-image" onclick="removeSeoImage()">
                        <i class="fa fa-times"></i>
                    </button>
                @endif

            </div>
        </div>
        <div class="avatar-edit">
            <input type="file"
                   class="profilePicUpload"
                   name="{{ $name }}"
                   id="{{ $id }}"
                   accept="{{ $accept }}"
                   onchange="previewSeoImage(event)"
                   @required($required)>

            <label for="{{ $id }}" class="bg--primary">
                @lang('Upload Image')
            </label>

            <small class="mt-2">
                @lang('Supported files:') 
                <b>{{ str_replace(',', ', ', $accept) }}</b>.
                @lang('Image will be resized into') {{ $size }}@lang('px').
            </small>
        </div>

    </div>
</div>
<script src="{{asset('assets/global/js/main.js')}}"></script>
<script>
function previewSeoImage(event) {
    const input = event.target;
    const preview = document.getElementById(input.id + 'Preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.style.backgroundImage = `url(${e.target.result})`;
            // Add remove button if not present
            if (!preview.querySelector('.remove-image')) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'remove-image';
                btn.innerHTML = '<i class="fa fa-times"></i>';
                btn.onclick = removeSeoImage;
                preview.appendChild(btn);
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeSeoImage() {
    const preview = document.getElementById('profilePicUpload1Preview');
    preview.style.backgroundImage = '';
    const btn = preview.querySelector('.remove-image');
    if (btn) btn.remove();

    const input = document.getElementById('profilePicUpload1');
    if (input) input.value = '';
}
</script>
