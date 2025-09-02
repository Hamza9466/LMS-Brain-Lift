<?php

namespace App\Providers;

use App\Models\QnaThread;
use App\Models\CourseCategory;
use App\Models\DiscussionThread;
use App\Models\UserNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;   // ✅ use the Laravel Cache facade

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        // === Cart mini data (unchanged) ===
        View::composer('*', function ($view) {
            $cart  = session('cart', []);
            $count = array_sum(array_map(fn($i) => (int)($i['qty'] ?? 1), $cart));
            $total = array_reduce($cart, fn($c,$i)=> $c + ((float)$i['price'] * (int)($i['qty'] ?? 1)), 0.0);

            $view->with('cartItems', $cart)
                 ->with('cartCount', $count)
                 ->with('cartTotal', $total);
        });

        // === Student unread notifications badge ===
        View::composer('*', function ($view) {
            $count = 0;
            if (Auth::check() && Schema::hasTable('user_notifications')) {
                $userId = Auth::id();
                // cache for 30s to avoid duplicate queries per request
                $count = Cache::remember("notif_unread_{$userId}", 30, function () use ($userId) {
                    return UserNotification::where('user_id', $userId)
                        ->whereNull('read_at')
                        ->count();
                });
            }
            $view->with('notifUnreadCount', (int) $count);
        });

        // === Admin/Teacher red counters for Q&A + Discussions ===
        View::composer('*', function ($view) {
            $qnaModNewCount  = 0;
            $discModNewCount = 0;

            $u = Auth::user();
            if (!$u || !in_array($u->role, ['admin', 'teacher'])) {
                return $view->with('qnaModNewCount', 0)
                            ->with('discModNewCount', 0);
            }

            // Required base tables must exist
            $hasUsers     = Schema::hasTable('users');
            $hasLessons   = Schema::hasTable('lessons');
            $hasSections  = Schema::hasTable('sections');

            if (!$hasUsers || !$hasLessons || !$hasSections) {
                return $view->with('qnaModNewCount', 0)
                            ->with('discModNewCount', 0);
            }

            // ---------- Q&A counter ----------
            if (Schema::hasTable('qna_threads')) {
                $q = QnaThread::query()
                    ->join('users', 'users.id', '=', 'qna_threads.user_id')      // who asked
                    ->join('lessons', 'lessons.id', '=', 'qna_threads.lesson_id')
                    ->join('sections', 'sections.id', '=', 'lessons.section_id')
                    ->where('users.role', 'student');                            // only student-created threads

                // limit to teacher’s assigned courses if the mapping table exists
                if ($u->role === 'teacher' && Schema::hasTable('course_assignments')) {
                    $courseIds = DB::table('course_assignments')
                        ->where('teacher_id', $u->id)
                        ->pluck('course_id');

                    if ($courseIds->isNotEmpty()) {
                        $q->whereIn('sections.course_id', $courseIds);
                    } else {
                        $q->whereRaw('1=0'); // no assigned → zero
                    }
                }

                // count only items created after last "seen"
                $lastSeenQna = Cache::get("qna_seen_at_{$u->id}");
                if (!empty($lastSeenQna)) {
                    $q->where('qna_threads.created_at', '>', $lastSeenQna);
                }

                $qnaModNewCount = (int) $q->count();
            }

            // ---------- Discussions counter ----------
            if (Schema::hasTable('discussion_threads')) {
                $d = DiscussionThread::query()
                    ->join('users', 'users.id', '=', 'discussion_threads.user_id')
                    ->join('lessons', 'lessons.id', '=', 'discussion_threads.lesson_id')
                    ->join('sections', 'sections.id', '=', 'lessons.section_id')
                    ->where('users.role', 'student');

                if ($u->role === 'teacher' && Schema::hasTable('course_assignments')) {
                    $courseIds = DB::table('course_assignments')
                        ->where('teacher_id', $u->id)
                        ->pluck('course_id');

                    if ($courseIds->isNotEmpty()) {
                        $d->whereIn('sections.course_id', $courseIds);
                    } else {
                        $d->whereRaw('1=0');
                    }
                }

                $lastSeenDisc = Cache::get("disc_seen_at_{$u->id}");
                if (!empty($lastSeenDisc)) {
                    $d->where('discussion_threads.created_at', '>', $lastSeenDisc);
                }

                $discModNewCount = (int) $d->count();
            }

            $view->with('qnaModNewCount', $qnaModNewCount)
                 ->with('discModNewCount', $discModNewCount);
        });
        
        // Navbar categories (only titles)
        // =====================
        View::composer(['website.*','layouts.website','partials.website.*'], function ($view) {
        $navs = Cache::remember('header_categories', 3600, function () {
            return CourseCategory::select('id','name','slug')->orderBy('name')->get();
        });
        $view->with('navs', $navs);
    });
    
    }
}