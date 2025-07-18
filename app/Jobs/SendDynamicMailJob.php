<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use App\Mail\StatusUpdatedMail;
use App\Mail\WelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendDynamicMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;

    public $user;
    public $token;
    public $post;

    public function __construct($type, $options = [])
    {
        $this->type = $type;

        // Truyền biến nào thì gán biến đó
        $this->user = $options['user'] ?? null;
        $this->token = $options['token'] ?? null;
        $this->post = $options['post'] ?? null;
    }

    public function handle(): void
    {
        match ($this->type) {
            'reset' => $this->sendResetMail(),
            'welcome' => $this->sendWelcomeMail(),
            'status' => $this->sendStatusUpdatedMail(),
            default => null,
        };
    }

    protected function sendResetMail()
    {
        if ($this->user && $this->user->email && $this->token) {
            Mail::to($this->user->email)->queue(new ResetPasswordMail($this->user, $this->token));
        }
    }

    protected function sendWelcomeMail()
    {
        if ($this->user && $this->user->email) {
            Mail::to($this->user->email)->queue(new WelcomeMail($this->user));
        }
    }

    protected function sendStatusUpdatedMail()
    {
        if ($this->post && $this->post->user && $this->post->user->email) {
            Mail::to($this->post->user->email)->queue(new StatusUpdatedMail($this->post));
        }
    }
}
