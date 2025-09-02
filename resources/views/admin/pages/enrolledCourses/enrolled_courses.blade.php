@extends('admin.layouts.main')

@section('content')
<div class="dashboard-content">
  <div class="container">
    <h4 class="dashboard-title">Enrolled Courses</h4>

    <div class="dashboard-course">
      <div class="dashboard-tabs-menu">
        <ul><li><a class="active" href="#">All Courses</a></li></ul>
      </div>

      <div class="dashboard-course-list">
        @forelse($courses as $course)
          @php
            $rating = (float)($course->rating_avg ?? 0);
            $ratingWidth = max(0, min(100, ($rating / 5) * 100));

            $totalLessons     = $course->lessons_count ?? ($course->total_lessons ?? 0);
            $completedLessons = $completedByCourse[$course->id] ?? 0;
            $progress         = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            $thumb = $course->thumbnail
                      ? asset('storage/'.$course->thumbnail)
                      : asset('assets/images/courses/default-thumbnail.jpg');

            // optional map passed from controller: [course_id => Review model/array]
            $my = ($myReviewsByCourse[$course->id] ?? null) ?? null;
          @endphp

          <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
              <div class="row g-3 align-items-center">
              <div class="col-md-3">
  <a href="{{ route('student.courses.show', $course->id) }}" class="d-block" title="Open course">
    <img src="{{ $thumb }}" alt="{{ $course->title }}" class="img-fluid rounded"
         style="object-fit:cover; width:100%; height:160px; cursor:pointer;">
  </a>
</div>

                <div class="col-md-9">
                  <div class="d-flex align-items-center mb-2">
                    <div class="me-2 text-warning">
                      <div class="rating-star" style="width:100px; height:18px; position:relative; background:#e9ecef; border-radius:3px; overflow:hidden;">
                        <div class="rating-label" style="position:absolute; top:0; left:0; height:100%; width: {{ $ratingWidth }}%; background:#ffc107;"></div>
                      </div>
                    </div>
                    <h5 class="mb-0 ms-2 text-truncate" style="max-width: 100%;">
                      {{ $course->title }}
                    </h5>
                  </div>

                  <div class="d-flex flex-wrap align-items-center gap-4 mt-2">
                    <div class="text-muted">
                      <div> <span class="fw-semibold">Total Lessons:</span> {{ $totalLessons }} </div>
                      <div> <span class="fw-semibold">Completed Lessons:</span> {{ $completedLessons }}/{{ $totalLessons }} </div>
                    </div>

                    <div class="flex-grow-1">
                      <div class="progress" style="height:8px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                      <div class="small text-muted mt-1">{{ $progress }}% Complete</div>
                    </div>

                    <div class="ms-auto">
                      <button type="button"
                              class="btn btn-warning text-dark open-review-modal"
                              data-bs-toggle="modal" data-bs-target="#reviewModal"
                              data-course-id="{{ $course->id }}"
                              data-course-title="{{ $course->title }}"
                              data-rating="{{ $my->rating ?? '' }}"
                              data-rtitle="{{ $my->title ?? '' }}"
                              data-rcomment="{{ $my->comment ?? '' }}">
                        {{ $my ? 'Edit Review' : 'Write Review' }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center text-muted py-5">No purchased courses yet.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

{{-- ========================= Review Modal ========================= --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="reviewModalLabel">Write a review</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="reviewForm" method="POST">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="course_id" id="rv-course-id">

          <div class="mb-3">
            <label class="form-label d-block">Your rating *</label>
            <div class="d-flex gap-3" id="rv-rating-group">
              @for ($i = 1; $i <= 5; $i++)
                <label class="d-inline-flex align-items-center" style="cursor:pointer;">
                  <input type="radio" name="rating" value="{{ $i }}" class="me-1"> {{ $i }}★
                </label>
              @endfor
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Title (optional)</label>
            <input type="text" class="form-control" name="title" id="rv-title">
          </div>

          <div class="mb-3">
            <label class="form-label">Comment (optional)</label>
            <textarea class="form-control" rows="4" name="comment" id="rv-comment"></textarea>
          </div>

          {{-- If you redirect back with errors, you can reuse this flag to auto-open modal --}}
          <input type="hidden" name="from_modal" value="1">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit" id="rv-submit">Save Review</button>
        </div>
      </form>
    </div>
  </div>
</div>
{{-- =============================================================== --}}

{{-- Modal script: set action + prefill --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl = document.getElementById('reviewModal');
  const form    = document.getElementById('reviewForm');
  const titleEl = document.getElementById('reviewModalLabel');
  const cidEl   = document.getElementById('rv-course-id');
  const rtitle  = document.getElementById('rv-title');
  const rcomm   = document.getElementById('rv-comment');

  const makeAction = (id) => "{{ url('/courses') }}/" + id + "/reviews"; // POST to reviews.store

  document.querySelectorAll('.open-review-modal').forEach(btn => {
    btn.addEventListener('click', () => {
      const courseId    = btn.getAttribute('data-course-id');
      const courseTitle = btn.getAttribute('data-course-title') || 'Course';
      const rating      = btn.getAttribute('data-rating') || '';
      const title       = btn.getAttribute('data-rtitle') || '';
      const comment     = btn.getAttribute('data-rcomment') || '';

      form.setAttribute('action', makeAction(courseId));
      titleEl.textContent = (rating ? 'Edit review — ' : 'Write review — ') + courseTitle;

      cidEl.value = courseId;
      rtitle.value = title;
      rcomm.value  = comment;

      // set rating radio
      document.querySelectorAll('#rv-rating-group input[type=radio]').forEach(r => {
        r.checked = (String(r.value) === String(rating));
      });
    });
  });

  // Optional: reopen modal with old inputs after validation errors
  @if ($errors->any() && old('from_modal') === '1' && old('course_id'))
    const oldCourseId = "{{ old('course_id') }}";
    form.setAttribute('action', makeAction(oldCourseId));
    cidEl.value = oldCourseId;
    rtitle.value = @json(old('title'));
    rcomm.value  = @json(old('comment'));
    const oldRating = "{{ old('rating') }}";
    document.querySelectorAll('#rv-rating-group input[type=radio]').forEach(r => {
      r.checked = (String(r.value) === String(oldRating));
    });
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  @endif
});
</script>
@endsection
