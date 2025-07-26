<?php
namespace App\View\Components;

use Closure;
use Illuminate\View\Component;

class LikeButton extends Component
{
    public $likeable;
    public $isLike;

    public function __construct($likeable, $isLike = true)
    {
        $this->likeable = $likeable;
        $this->isLike = $isLike;
    }

    public function render(): \Illuminate\Contracts\View\View|Closure|string
    {
        return view('components.like-button');
    }
}
