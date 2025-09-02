@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-primary">Lessons</h4>
        <a href="{{ route('courses.index') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> All Courses
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead style="background: linear-gradient(90deg, #02409c, #12a0a0); color: #fff;">
                    <tr>
                        <th>#</th>
                        <th>Section</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th style="min-width:240px;">Media</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lessons as $lesson)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $lesson->section->title ?? 'N/A' }}</td>
                            <td>{{ $lesson->title }}</td>
                            <td>{{ ucfirst($lesson->type) }}</td>

                            <td>
                                @if($lesson->type === 'video')
                                    @if(!empty($lesson->video_file))
                                        <video width="220" controls preload="metadata" style="max-height:140px">
                                            <source src="{{ asset('storage/' . $lesson->video_file) }}">
                                            Your browser does not support the video tag.
                                        </video>
                                    @elseif(!empty($lesson->video_path))
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
                                            <iframe width="220" height="124"
                                                    src="https://www.youtube.com/embed/{{ $videoId }}"
                                                    title="YouTube video player"
                                                    frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                    allowfullscreen></iframe>
                                        @else
                                            <a href="{{ $lesson->video_path }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                Open Video
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted">No media available</span>
                                    @endif
                                @elseif($lesson->type === 'pdf')
                                    @if(!empty($lesson->pdf_path))
                                        <a href="{{ asset('storage/' . $lesson->pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            View PDF
                                        </a>
                                    @else
                                        <span class="text-muted">No media available</span>
                                    @endif
                                @else
                                    <span class="text-muted">No media available</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $owns = optional($lesson->section?->course)->teacher_id === auth()->id();
                                    $isAdmin = auth()->user()->role === 'admin';
                                @endphp

                                @if($isAdmin || $owns)
                                    <a href="{{ route('lessons.edit', $lesson->id) }}" class="text-primary me-2" title="Edit">
                                        <i class="fas fa-edit fa-lg"></i>
                                    </a>
                                @endif

                                @if($isAdmin)
                                    <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete" onclick="return confirm('Delete this lesson?')">
                                            <i class="fas fa-trash-alt fa-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No lessons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lessons instanceof \Illuminate\Pagination\AbstractPaginator)
            <div class="d-flex justify-content-center mt-3">
                {{ $lessons->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
