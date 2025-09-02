<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessLessonUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public $lessonId;

    /** @var string path relative to the "local" disk (e.g., tmp/lessons/vid_xxx.mp4) */
    public $tempFilePath;

    /** @var 'video'|'pdf' */
    public $type;

    /** Increase for large files */
    public $timeout = 1200; // seconds
    public $tries = 1;

    public function __construct(int $lessonId, string $tempFilePath, string $type)
    {
        $this->lessonId     = $lessonId;
        $this->tempFilePath = $tempFilePath;
        $this->type         = $type;
    }

    public function handle(): void
    {
        $lesson = Lesson::find($this->lessonId);

        if (!$lesson) {
            Log::warning('ProcessLessonUpload: lesson not found', ['lessonId' => $this->lessonId]);
            return;
        }

        if (!Storage::disk('local')->exists($this->tempFilePath)) {
            Log::warning('ProcessLessonUpload: temp file missing', ['temp' => $this->tempFilePath]);
            return;
        }

        // Ensure final directory exists and compute destination path
        $filename = basename($this->tempFilePath);
        $destPath = $this->type === 'video'
            ? "lessons/videos/{$filename}"
            : "lessons/{$filename}";

        // Stream copy from local->public (prevents loading entire file into memory)
        $readStream = Storage::disk('local')->readStream($this->tempFilePath);
        if ($readStream === false) {
            Log::error('ProcessLessonUpload: failed to open read stream', ['temp' => $this->tempFilePath]);
            return;
        }

        $ok = Storage::disk('public')->put($destPath, $readStream);
        if (is_resource($readStream)) {
            fclose($readStream);
        }

        if (!$ok) {
            Log::error('ProcessLessonUpload: failed to write to public disk', ['dest' => $destPath]);
            return;
        }

        // Remove temp file
        Storage::disk('local')->delete($this->tempFilePath);

        // Update DB (and clear other media fields)
        if ($this->type === 'video') {
            // Optionally remove old uploaded video
            if ($lesson->video_file && $lesson->video_file !== $destPath && Storage::disk('public')->exists($lesson->video_file)) {
                // Silently ignore delete errors
                Storage::disk('public')->delete($lesson->video_file);
            }

            $lesson->update([
                'video_file' => $destPath, // ✅ uploaded file path
                'video_path' => null,      // link cleared
                'pdf_path'   => null,      // pdf cleared
            ]);
        } else { // pdf
            if ($lesson->pdf_path && $lesson->pdf_path !== $destPath && Storage::disk('public')->exists($lesson->pdf_path)) {
                Storage::disk('public')->delete($lesson->pdf_path);
            }

            $lesson->update([
                'pdf_path'   => $destPath,
                'video_file' => null,
                'video_path' => null,
            ]);
        }
    }
}