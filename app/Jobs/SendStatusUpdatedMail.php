<?php

namespace App\Jobs;

use App\Mail\StatusUpdatedMail;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendStatusUpdatedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle()
    {
        if ($this->post->user && $this->post->user->email) {
            Mail::to($this->post->user->email)
                ->queue(new StatusUpdatedMail($this->post));
                //->onQueue('SendStatusUpdatedMail'); // Cùng queue với job hiện tại
        }
    }
}

