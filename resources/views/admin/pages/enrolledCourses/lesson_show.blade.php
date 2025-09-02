@extends('admin.layouts.main')

@section('content')
<div class="container py-4">
    <a href="{{ route('student.sections.show', $lesson->section_id) }}" class="small">&larr; Back to section</a>

    <h3 class="mt-2">{{ $course->title }}</h3>
    <h5 class="text-muted">{{ $lesson->section->title }}</h5>
    <h4 class="mt-3">{{ $lesson->title }}</h4>

    {{-- Flash messages --}}
    @if(session('error'))
      <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    {{-- Q&A + Discussions --}}
    <div class="mt-3 d-flex gap-2">
      <a href="{{ route('student.qna.index', $lesson->id) }}" class="btn btn-outline-primary">Q&A for this Lesson</a>
      <a href="{{ route('student.discussion.index', $lesson->id) }}" class="btn btn-outline-secondary">Discussions</a>
    </div>

    {{-- ===== Media (Video / PDF) ===== --}}
    <div class="mt-3">
        @php
            $embed = null;
            $isYoutube = false;

            // is this lesson a playable video (uploaded file or a link)?
            $isVideo = $lesson->type === 'video' && (
                !empty($lesson->video_file) || !empty($lesson->video_path)
            );

            // Resolve YouTube embed if video_path looks like YouTube
            if ($lesson->type === 'video' && !empty($lesson->video_path)) {
                $u = trim($lesson->video_path);
                if (preg_match('~youtu\.be/([^?&/]+)|v=([^?&/]+)|/shorts/([^?&/]+)|/embed/([^?&/]+)~i', $u, $m)) {
                    $id = $m[1] ?? $m[2] ?? $m[3] ?? $m[4];
                    if ($id) {
                        $embed = "https://www.youtube-nocookie.com/embed/{$id}?enablejsapi=1&rel=0";
                        $isYoutube = true;
                    }
                }
            }
        @endphp

        @if($lesson->type === 'video')
            @if(!empty($lesson->video_file))
                {{-- Uploaded file --}}
                <div class="ratio ratio-16x9">
                    <video id="html5-player" controls preload="metadata" style="width:100%; max-height:480px;">
                        <source src="{{ asset('storage/' . $lesson->video_file) }}">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @elseif(!empty($lesson->video_path))
                @if($embed)
                    {{-- YouTube --}}
                    <div class="ratio ratio-16x9">
                        <iframe id="yt-player" src="{{ $embed }}" title="YouTube video"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen style="border:0"></iframe>
                    </div>
                @else
                    {{-- Other providers (Vimeo, direct link, etc.) --}}
                    <a href="{{ $lesson->video_path }}" target="_blank" rel="noopener" class="btn btn-outline-secondary">
                        Open Video
                    </a>
                @endif
            @else
                <div class="text-muted">No media available.</div>
            @endif

        @elseif($lesson->type === 'pdf' && !empty($lesson->pdf_path))
            <div class="d-flex gap-2">
                <a class="btn btn-outline-secondary" target="_blank"
                   href="{{ asset('storage/'.$lesson->pdf_path) }}">View PDF</a>
                <a class="btn btn-primary"
                   href="{{ route('student.lessons.download', $lesson->id) }}">Download PDF</a>
            </div>
        @else
            <div class="text-muted">No media available.</div>
        @endif
    </div>

    {{-- ===== Next controls ===== --}}
    @php
        // From controller:
        // $progressPercent, $watchedOK, $showQuizFlag
        // $quizId, $nextSection
        // $canSeeQuiz, $isCompleted
    @endphp

    @if($isVideo)
        {{-- Only for video: show watch gating --}}
        <div class="mt-3 d-flex align-items-center gap-3" @if($showQuizFlag ?? false) style="display:none" @endif>
            <div>
                <span class="small text-muted">Watched:</span>
                <span id="watchPercent" class="fw-semibold">{{ number_format($progressPercent ?? 0, 2) }}%</span>
            </div>
            <button id="nextBtn" class="btn btn-primary" {{ ($watchedOK ?? false) ? '' : 'disabled' }}>
                Next
            </button>
        </div>

        @if(!empty($quizId))
            {{-- Auto-start quiz attempt when Next is clicked --}}
            <form id="startQuizForm" method="POST" action="{{ route('student.quizzes.start', $quizId) }}" style="display:none;">
                @csrf
                <input type="hidden" name="redirect" value="{{ route('student.lessons.show', $lesson->id) }}?showquiz=1">
                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
            </form>
        @endif

    @else
        {{-- Non-video (e.g., PDF): no watch requirement --}}
        @if(!empty($quizId))
            {{-- There IS a quiz -> require pass to unlock next, but no watch gating --}}
            <div class="mt-3" @if($showQuizFlag ?? false) style="display:none" @endif>
              <form id="startQuizForm" method="POST" action="{{ route('student.quizzes.start', $quizId) }}">
                @csrf
                <input type="hidden" name="redirect" value="{{ route('student.lessons.show', $lesson->id) }}?showquiz=1">
                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                <button class="btn btn-primary">Start MCQs</button>
              </form>
            </div>
        @else
            {{-- No quiz -> let student mark as read and continue --}}
            <div class="mt-3 d-flex align-items-center gap-3">
                <button id="markReadBtn" class="btn btn-success">
                    Mark as Read & Continue
                </button>
                @if(isset($nextSection) && $nextSection)
                  <a id="continueLink" href="{{ route('student.sections.show', $nextSection->id) }}"
                     class="btn btn-outline-success" style="display:none;">
                    Continue to Next Section
                  </a>
                @endif
            </div>
        @endif
    @endif

    {{-- Description --}}
    @if($lesson->description)
        <p class="mt-3">{{ $lesson->description }}</p>
    @endif

    {{-- ===== Completion (note: your controller marks complete only after quiz pass / manual complete) ===== --}}
    <div class="mt-3">
      @if($isCompleted)
        <span class="badge bg-success">Lesson Completed</span>
      @else
        <span class="text-muted">Lesson completes automatically when you pass the quiz.</span>
      @endif
    </div>

    {{-- ===== MCQs (loaded only when allowed) ===== --}}
    @if(($canSeeQuiz ?? false) && isset($quiz) && $quiz)
        <hr class="my-4">

        {{-- Attempt in-progress --}}
        @if(isset($attempt) && $attempt && $attempt->status === 'in_progress')
            <h5 class="mb-3">Section Quiz: {{ $quiz->title }}</h5>
            @if($quiz->duration_minutes)
                <div class="text-muted small mb-2">Time limit: {{ $quiz->duration_minutes }} minutes</div>
            @endif

            <form method="POST" action="{{ route('student.attempts.submit', $attempt->id) }}">
                @csrf
                @foreach($questions as $i => $q)
                    @php
                        $opts = $quiz->shuffle_options ? $q->options->shuffle() : $q->options;
                        $name = "answers[{$q->id}][]";
                    @endphp

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="fw-semibold mb-2">Q{{ $i+1 }}. {!! nl2br(e($q->text)) !!}</div>

                            @if($q->type === 'single' || $q->type === 'true_false')
                                @foreach($opts as $op)
                                  <div class="form-check mb-1">
                                    <input class="form-check-input" type="radio" name="{{ $name }}" value="{{ $op->id }}" id="q{{ $q->id }}o{{ $op->id }}">
                                    <label class="form-check-label" for="q{{ $q->id }}o{{ $op->id }}">{{ $op->text }}</label>
                                  </div>
                                @endforeach
                            @else
                                @foreach($opts as $op)
                                  <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="{{ $name }}" value="{{ $op->id }}" id="q{{ $q->id }}o{{ $op->id }}">
                                    <label class="form-check-label" for="q{{ $q->id }}o{{ $op->id }}">{{ $op->text }}</label>
                                  </div>
                                @endforeach
                            @endif

                            <div class="small text-muted mt-2">Points: {{ rtrim(rtrim((string)$q->points,'0'),'.') }}</div>
                        </div>
                    </div>
                @endforeach

                <div class="text-end">
                    <button class="btn btn-primary">Submit Quiz</button>
                </div>
            </form>

        {{-- Last attempt submitted --}}
        @elseif(isset($lastAttempt) && $lastAttempt && $lastAttempt->status === 'submitted')
            <div class="alert {{ $lastAttempt->is_passed ? 'alert-success' : 'alert-danger' }}">
                Result: <strong>{{ rtrim(rtrim((string)$lastAttempt->percentage,'0'),'.') }}%</strong> —
                {!! $lastAttempt->is_passed ? 'You passed! 🎉' : 'You did not pass.' !!}
            </div>

            @if($lastAttempt->is_passed)
                @if(isset($nextSection) && $nextSection)
                    <a href="{{ route('student.sections.show', $nextSection->id) }}" class="btn btn-outline-success">
                        Continue to Next Section
                    </a>
                @else
                    <span class="text-muted">No more sections in this course.</span>
                @endif
            @else
                @php
                    $canRetryNow = (!$lastAttempt->is_passed) && (($limit ?? 0) == 0 || ($attemptsUsed ?? 0) < $limit);
                @endphp
                @if($canRetryNow)
                    <form method="POST" action="{{ route('student.quizzes.start', $quiz->id) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ route('student.lessons.show', $lesson->id) }}?showquiz=1">
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                        <button class="btn btn-outline-primary">Reattempt MCQs</button>
                    </form>
                @else
                    <div class="text-muted">
                        No attempts remaining.
                        @if(($limit ?? 0) > 0)
                          <span class="small">(Used {{ $attemptsUsed }} / {{ $limit }})</span>
                        @endif
                    </div>
                @endif
            @endif

        {{-- No attempt yet --}}
        @else
            @php $canStart = (($limit ?? 0) == 0 || ($attemptsUsed ?? 0) < $limit); @endphp
            @if($canStart)
                <div class="text-muted">
                  Click <strong>{{ $isVideo ? 'Next' : (!empty($quizId) ? 'Start MCQs' : 'Mark as Read & Continue') }}</strong> above to proceed.
                </div>
            @else
                <div class="text-muted">
                    Quiz is not available. No attempts remaining.
                    @if(($limit ?? 0) > 0)
                        <span class="small">(Used {{ $attemptsUsed }} / {{ $limit }})</span>
                    @endif
                </div>
            @endif
        @endif
    @endif
</div>

{{-- ===== Scripts ===== --}}

{{-- Video progress tracking (YouTube + HTML5) — only for video lessons --}}
@if($isVideo)
<script>
(function(){
  // If YouTube iframe exists, load its API
  if (document.getElementById('yt-player')) {
    const tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    document.head.appendChild(tag);
  }

  let maxPercent = {{ (float)($progressPercent ?? 0) }};
  const nextBtn   = document.getElementById('nextBtn');
  const percentEl = document.getElementById('watchPercent');
  const PROGRESS_URL = @json(route('student.lessons.progress', $lesson->id));
  const CSRF = @json(csrf_token());

  let sending = false;
  async function sendProgress(pct){
    sending = true;
    try {
      await fetch(PROGRESS_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ percent: pct })
      });
    } catch(_) {} finally { sending = false; }
  }

  function bump(pct, force=false){
    pct = Math.max(0, Math.min(100, pct || 0));
    if (pct > maxPercent || force) {
      maxPercent = Math.max(maxPercent, pct);
      if (percentEl) percentEl.textContent = maxPercent.toFixed(2) + '%';
      if (maxPercent >= 90 && nextBtn && nextBtn.hasAttribute('disabled')) {
        nextBtn.removeAttribute('disabled');
      }
      return sendProgress(maxPercent);
    }
    return Promise.resolve();
  }

  // HTML5 <video>
  const html5 = document.getElementById('html5-player');
  if (html5) {
    let lastSent = 0;
    const SEND_INTERVAL_MS = 5000;

    function currentPercent() {
      const dur = html5.duration || 0;
      const cur = html5.currentTime || 0;
      if (!dur || dur < 1) return 0;
      return (cur / dur) * 100;
    }

    let timer = null;
    function startTimer(){
      if (timer) return;
      timer = setInterval(async () => {
        if (!sending) {
          const pct = currentPercent();
          if (pct > maxPercent + 0.2) {
            await bump(pct);
            lastSent = Date.now();
          } else if (Date.now() - lastSent > SEND_INTERVAL_MS) {
            await bump(maxPercent, true);
            lastSent = Date.now();
          }
        }
      }, 2000);
    }
    function stopTimer(){ if (timer) { clearInterval(timer); timer = null; } }

    html5.addEventListener('play', startTimer);
    html5.addEventListener('pause', async () => { stopTimer(); await bump(currentPercent()); });
    html5.addEventListener('ended', async () => { stopTimer(); await bump(100, true); });
    window.addEventListener('beforeunload', () => { bump(currentPercent(), true); }, { passive:true });
  }

  // YouTube IFrame
  let ytPlayer;
  window.onYouTubeIframeAPIReady = function(){
    const el = document.getElementById('yt-player');
    if (!el) return;
    ytPlayer = new YT.Player('yt-player', {
      events: { onReady, onStateChange }
    });
  };
  function onReady(){
    setInterval(updateYTProgress, 5000);
    window.addEventListener('beforeunload', () => updateYTProgress(true), {passive:true});
  }
  function onStateChange(e){
    if (e.data === YT.PlayerState.PAUSED || e.data === YT.PlayerState.ENDED) updateYTProgress();
  }
  async function updateYTProgress(force=false){
    if (!ytPlayer || sending) return;
    const dur = ytPlayer.getDuration ? ytPlayer.getDuration() : 0;
    const cur = ytPlayer.getCurrentTime ? ytPlayer.getCurrentTime() : 0;
    if (!dur || dur < 1) return;
    const pct = (cur / dur) * 100;
    if (force) {
      await bump(Math.max(maxPercent, pct), true);
    } else if (pct > maxPercent) {
      await bump(pct);
    }
  }

  // Next button: save progress, then start quiz or show quiz
  nextBtn?.addEventListener('click', async function(){
    if (typeof updateYTProgress === 'function') { await updateYTProgress(true); }
    const f = document.getElementById('startQuizForm');
    if (f) { f.submit(); return; }
    const url = new URL(window.location.href);
    url.searchParams.set('showquiz','1');
    window.location.href = url.toString();
  });
})();
</script>
@endif

{{-- PDF: Mark as Read (no quiz) --}}
@if($lesson->type === 'pdf' && empty($quizId))
<script>
(function(){
  const btn = document.getElementById('markReadBtn');
  if (!btn) return;

  const PROGRESS_URL = @json(route('student.lessons.progress', $lesson->id));
  const CSRF = @json(csrf_token());
  const continueLink = document.getElementById('continueLink');

  btn.addEventListener('click', async function(){
    btn.disabled = true;
    btn.textContent = 'Saving...';
    try {
      await fetch(PROGRESS_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ percent: 100 })
      });

      if (continueLink) {
        continueLink.style.display = '';
        continueLink.click(); // auto-redirect
      } else {
        window.location.reload();
      }
    } catch (e) {
      btn.disabled = false;
      btn.textContent = 'Mark as Read & Continue';
      alert('Could not save progress. Please try again.');
    }
  });
})();
</script>
@endif
@endsection
