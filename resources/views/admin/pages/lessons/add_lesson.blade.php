@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4>Add Lesson</h4>

    <form method="POST" action="{{ route('lessons.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Section</label>
            <select name="section_id" class="form-select border-1 bg-white" required>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->title }}</option>
                @endforeach
            </select>
            @error('section_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        @isset($course)
        <div class="mb-3">
            <label class="form-label">Course</label>
            <input type="text" class="form-control border-1 bg-white" value="{{ $course->title }}" disabled>
        </div>
        @endisset

        <div class="mb-3">
            <label class="form-label">Lesson Title</label>
            <input type="text" name="title" class="form-control border-1 bg-white" required>
            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control border-1 bg-white" rows="3"></textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Type --}}
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" id="type" class="form-select border-1 bg-white" required>
                <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video</option>
                <option value="pdf"   {{ old('type') === 'pdf'   ? 'selected' : '' }}>PDF</option>
            </select>
            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- VIDEO MODE TOGGLE (only when type=video) --}}
        <div class="mb-3" id="videoModeGroup">
            <label class="form-label d-block">Video Source</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="video_mode" id="videoModeLink" value="link"
                       {{ old('video_mode','link') === 'link' ? 'checked' : '' }}>
                <label class="form-check-label" for="videoModeLink">Link (YouTube/Vimeo)</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="video_mode" id="videoModeFile" value="file"
                       {{ old('video_mode') === 'file' ? 'checked' : '' }}>
                <label class="form-check-label" for="videoModeFile">Upload File</label>
            </div>
        </div>

        {{-- Video URL --}}
        <div class="mb-3" id="videoUrlGroup">
            <label class="form-label">Video URL</label>
            <input type="url"
                   name="video_path"
                   id="video_path"
                   class="form-control border-1 bg-white"
                   placeholder="https://www.youtube.com/watch?v=..."
                   value="{{ old('video_path') }}">
            <small class="text-muted">Provide a valid URL if using a hosted video.</small>
            @error('video_path') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>

     {{-- Video File --}}
<div class="mb-3 {{ $errors->has('video_file') ? '' : 'd-none' }}" id="videoFileGroup">
    <label class="form-label">Upload Video</label>
    <input type="file" name="video_file" id="video_file"
           class="form-control border-1 bg-white @error('video_file') is-invalid @enderror"
           accept="video/*">
    @error('video_file')
        <small class="text-danger d-block">{{ $message }}</small>
    @enderror
</div>

        {{-- PDF --}}
        <div class="mb-3 d-none" id="pdfGroup">
            <label class="form-label">Upload PDF</label>
            <input type="file" name="media" class="form-control border-1 bg-white" accept="application/pdf">
            @error('media') <small class="text-danger d-block">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Create Lesson</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const type = document.getElementById('type');

    const videoModeGroup = document.getElementById('videoModeGroup');
    const videoUrlGroup  = document.getElementById('videoUrlGroup');
    const videoFileGroup = document.getElementById('videoFileGroup');
    const pdfGroup       = document.getElementById('pdfGroup');

    const videoModeLink = document.getElementById('videoModeLink');
    const videoModeFile = document.getElementById('videoModeFile');

    const videoPathInput = document.getElementById('video_path');
    const videoFileInput = document.getElementById('video_file');

    function toggleVideoSubfields() {
        if (videoModeLink.checked) {
            // Link mode
            videoUrlGroup.classList.remove('d-none');
            videoFileGroup.classList.add('d-none');

            // Enable URL input; clear file input
            videoPathInput.disabled = false;
            if (videoFileInput) videoFileInput.value = '';
        } else {
            // File mode
            videoUrlGroup.classList.add('d-none');
            videoFileGroup.classList.remove('d-none');

            // Disable & clear URL input so it won't submit "" and trigger URL rule
            videoPathInput.value = '';
            videoPathInput.disabled = true;
        }
    }

    function toggleByType() {
        if (type.value === 'video') {
            videoModeGroup.classList.remove('d-none');
            pdfGroup.classList.add('d-none');
            toggleVideoSubfields();
        } else {
            videoModeGroup.classList.add('d-none');
            videoUrlGroup.classList.add('d-none');
            videoFileGroup.classList.add('d-none');
            pdfGroup.classList.remove('d-none');

            // Make sure URL input is disabled & cleared when switching to PDF
            videoPathInput.value = '';
            videoPathInput.disabled = true;
            if (videoFileInput) videoFileInput.value = '';
        }
    }

    type.addEventListener('change', toggleByType);
    if (videoModeLink) videoModeLink.addEventListener('change', toggleVideoSubfields);
    if (videoModeFile) videoModeFile.addEventListener('change', toggleVideoSubfields);

    // Initialize state based on old() or defaults
    toggleByType();
    // If the form reloaded with validation errors and video_mode=file, enforce disabled URL
    if (type.value === 'video' && videoModeFile && videoModeFile.checked) {
        videoPathInput.value = '';
        videoPathInput.disabled = true;
    }
});
</script>
@endsection
