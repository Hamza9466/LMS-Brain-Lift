@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <h4>Edit Lesson</h4>

    <form method="POST" action="{{ route('lessons.update', $lesson->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Section --}}
        <div class="mb-3">
            <label class="form-label">Section</label>
            <select name="section_id" class="form-select border-1 bg-white" required>
                @foreach($sections as $section)
                    <option value="{{ $section->id }}" {{ (old('section_id', $lesson->section_id) == $section->id) ? 'selected' : '' }}>
                        {{ $section->title }}
                    </option>
                @endforeach
            </select>
            @error('section_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Title --}}
        <div class="mb-3">
            <label class="form-label">Lesson Title</label>
            <input type="text" name="title" class="form-control border-1 bg-white"
                   value="{{ old('title', $lesson->title) }}" required>
            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control border-1 bg-white" rows="3">{{ old('description', $lesson->description) }}</textarea>
            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Type --}}
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" id="type" class="form-select border-1 bg-white" required>
                <option value="video" {{ old('type', $lesson->type) === 'video' ? 'selected' : '' }}>Video</option>
                <option value="pdf"   {{ old('type', $lesson->type) === 'pdf'   ? 'selected' : '' }}>PDF</option>
            </select>
            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- VIDEO SOURCE TOGGLE (only when type=video) --}}
        @php
            $defaultVideoMode = 'link';
            if (old('video_mode')) {
                $defaultVideoMode = old('video_mode');
            } elseif ($lesson->type === 'video') {
                $defaultVideoMode = $lesson->video_file ? 'file' : 'link';
            }
        @endphp

        <div class="mb-3" id="videoModeGroup">
            <label class="form-label d-block">Video Source</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input"
                       type="radio"
                       name="video_mode"
                       id="videoModeLink"
                       value="link"
                       {{ $defaultVideoMode === 'link' ? 'checked' : '' }}>
                <label class="form-check-label" for="videoModeLink">Link (YouTube/Vimeo)</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input"
                       type="radio"
                       name="video_mode"
                       id="videoModeFile"
                       value="file"
                       {{ $defaultVideoMode === 'file' ? 'checked' : '' }}>
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
                   value="{{ old('video_path', $lesson->video_path) }}">
            <small class="text-muted">Provide a valid URL if using a hosted video.</small>
            @error('video_path') <small class="text-danger d-block">{{ $message }}</small> @enderror

            {{-- Link preview if existing --}}
            @if($lesson->type === 'video' && $lesson->video_path)
                @php
                    $videoId = null;
                    $url = $lesson->video_path;
                    $host = parse_url($url, PHP_URL_HOST);
                    if ($host && str_contains($host, 'youtu.be')) {
                        $videoId = ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');
                    } elseif ($host && str_contains($host, 'youtube.com')) {
                        parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $q);
                        $videoId = $q['v'] ?? null;
                    }
                @endphp
                @if($videoId)
                    <div class="mt-2">
                        <iframe width="320" height="180"
                                src="https://www.youtube.com/embed/{{ $videoId }}"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen></iframe>
                    </div>
                @else
                    <div class="mt-2">
                        <a href="{{ $lesson->video_path }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            Open Current Video
                        </a>
                    </div>
                @endif
            @endif
        </div>

        {{-- Video File --}}
        <div class="mb-3 d-none" id="videoFileGroup">
            <label class="form-label">Upload Video (optional)</label>
            <input type="file" name="video_file" class="form-control border-1 bg-white" accept="video/*">
            <small class="text-muted">Allowed: MP4, MOV, AVI, MKV (adjust server limits as needed).</small>
            @error('video_file') <small class="text-danger d-block">{{ $message }}</small> @enderror

            {{-- Current uploaded file preview --}}
            @if($lesson->type === 'video' && $lesson->video_file)
                <div class="mt-2">
                    <video width="320" controls preload="metadata" style="max-height:200px">
                        <source src="{{ asset('storage/' . $lesson->video_file) }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
        </div>

        {{-- PDF --}}
        <div class="mb-3 d-none" id="pdfGroup">
            <label class="form-label">Replace PDF (optional)</label>
            <input type="file" name="media" class="form-control border-1 bg-white" accept="application/pdf">
            @error('media') <small class="text-danger d-block">{{ $message }}</small> @enderror

            @if($lesson->type === 'pdf' && $lesson->pdf_path)
                <div class="mt-2">
                    <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        Current PDF
                    </a>
                </div>
            @endif
        </div>

        <button class="btn btn-success">Update Lesson</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const type           = document.getElementById('type');
    const videoModeGroup = document.getElementById('videoModeGroup');
    const videoUrlGroup  = document.getElementById('videoUrlGroup');
    const videoFileGroup = document.getElementById('videoFileGroup');
    const pdfGroup       = document.getElementById('pdfGroup');

    const videoModeLink  = document.getElementById('videoModeLink');
    const videoModeFile  = document.getElementById('videoModeFile');

    function toggleVideoSubfields() {
        if (videoModeLink.checked) {
            videoUrlGroup.classList.remove('d-none');
            videoFileGroup.classList.add('d-none');
        } else {
            videoUrlGroup.classList.add('d-none');
            videoFileGroup.classList.remove('d-none');
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
        }
    }

    type.addEventListener('change', toggleByType);
    if (videoModeLink)  videoModeLink.addEventListener('change', toggleVideoSubfields);
    if (videoModeFile)  videoModeFile.addEventListener('change', toggleVideoSubfields);

    // initial state
    toggleByType();
});
</script>
@endsection
