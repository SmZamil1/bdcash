<div class="grid grid-cols-12 gap-4">
    @foreach($formData as $data)
        <div class="col-span-12 md:col-span-{{ $data->width === '100' ? '12' : ($data->width === '50' ? '6' : ($data->width === '33' ? '4' : ($data->width === '25' ? '3' : '12'))) }}">
            <div class="mb-3 relative">
                <label for="field-{{ $loop->index }}" class="block mb-2 text-sm font-semibold text-white/80">
                    {{ __($data->name) }}
                    @if($data->is_required === 'required')
                        <span class="text-red-400">*</span>
                    @endif
                    
                    @if(!empty($data->instruction))
                        <span class="ml-1 text-blue-300 cursor-pointer hover:text-blue-200 transition-colors" data-bs-toggle="tooltip" data-bs-title="{{ __($data->instruction) }}">
                            <i class="fas fa-info-circle"></i>
                        </span>
                    @endif
                </label>

                @if($data->type === 'text')
                    <input
                        type="text"
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        value="{{ old($data->label) }}"
                        @if($data->is_required === 'required') required @endif
                    >

                @elseif($data->type === 'textarea')
                    <textarea
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none min-h-[100px]"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        @if($data->is_required === 'required') required @endif
                    >{{ old($data->label) }}</textarea>

                @elseif($data->type === 'select')
                    <select
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-all focus:bg-white/10 outline-none appearance-none"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        @if($data->is_required === 'required') required @endif
                    >
                        <option value="" class="bg-gray-800 text-gray-300">Select {{ __($data->name) }}</option>
                        @foreach($data->options as $item)
                            <option value="{{ $item }}" class="bg-gray-800 text-white" @if($item == old($data->label)) selected @endif>{{ __($item) }}</option>
                        @endforeach
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white/50 top-[32px]">
                        <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>

                @elseif($data->type === 'checkbox')
                    <div class="flex flex-wrap gap-3 mt-2">
                        @foreach($data->options as $option)
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="{{ $data->label }}[]" 
                                    value="{{ $option }}" 
                                    id="field-{{ $loop->index }}-{{ $loop->iteration }}"
                                    class="w-4 h-4 text-blue-600 bg-white/10 border-white/30 rounded focus:ring-blue-500 focus:ring-2"
                                    @if(is_array(old($data->label)) && in_array($option, old($data->label))) checked @endif
                                >
                                <label for="field-{{ $loop->index }}-{{ $loop->iteration }}" class="ml-2 text-sm font-medium text-white/90">{{ $option }}</label>
                            </div>
                        @endforeach
                    </div>

                @elseif($data->type === 'radio')
                    <div class="flex flex-wrap gap-3 mt-2">
                        @foreach($data->options as $option)
                            <div class="flex items-center">
                                <input 
                                    type="radio" 
                                    name="{{ $data->label }}" 
                                    value="{{ $option }}" 
                                    id="field-{{ $loop->index }}-{{ $loop->iteration }}"
                                    class="w-4 h-4 text-blue-600 bg-white/10 border-white/30 focus:ring-blue-500 focus:ring-2"
                                    @if($option == old($data->label)) checked @endif
                                >
                                <label for="field-{{ $loop->index }}-{{ $loop->iteration }}" class="ml-2 text-sm font-medium text-white/90">{{ $option }}</label>
                            </div>
                        @endforeach
                    </div>

                @elseif($data->type === 'file')
                    <div class="relative group">
                        <label for="field-{{ $loop->index }}" class="flex flex-col items-center justify-center w-full h-32 border-2 border-white/20 border-dashed rounded-xl cursor-pointer bg-white/5 hover:bg-white/10 hover:border-blue-400 transition-all group-hover:bg-blue-500/5">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-3 text-white/60 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-2 text-sm text-white/70"><span class="font-semibold text-blue-300">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-white/50">
                                    Supported: {{ collect(explode(',', $data->extensions))->map(fn($ext) => '.' . trim($ext))->implode(', ') }}
                                </p>
                            </div>
                            <input
                                type="file"
                                class="hidden"
                                id="field-{{ $loop->index }}"
                                name="{{ $data->label }}"
                                @if($data->is_required === 'required') required @endif
                                accept="{{ collect(explode(',', $data->extensions))->map(fn($ext) => '.' . trim($ext))->implode(',') }}"
                                onchange="displayFileName(this, 'file-name-{{ $loop->index }}')"
                            >
                        </label>
                        <p id="file-name-{{ $loop->index }}" class="mt-2 text-xs text-green-400 font-medium text-center hidden"></p>
                    </div>

                @elseif($data->type === 'date')
                    <input
                        type="date"
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        value="{{ old($data->label) }}"
                        @if($data->is_required === 'required') required @endif
                    >

                @elseif($data->type === 'datetime')
                    <input
                        type="datetime-local"
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        value="{{ old($data->label) }}"
                        @if($data->is_required === 'required') required @endif
                    >

                @elseif($data->type === 'time')
                    <input
                        type="time"
                        class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none"
                        id="field-{{ $loop->index }}"
                        name="{{ $data->label }}"
                        value="{{ old($data->label) }}"
                        @if($data->is_required === 'required') required @endif
                    >
                @endif
                
                @error($data->label)
                    <span class="text-red-400 text-xs mt-1 block px-1">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endforeach
</div>

@push('script')
    <script>
        function displayFileName(input, outputId) {
            const fileName = input.files[0]?.name;
            const outputElement = document.getElementById(outputId);
            if (fileName) {
                outputElement.textContent = 'Selected: ' + fileName;
                outputElement.classList.remove('hidden');
            } else {
                outputElement.classList.add('hidden');
            }
        }
        
        // Initialize tooltips (optional if globally initialized)
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
            // Check if bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Tooltip(el);
            }
        });
    </script>
@endpush
