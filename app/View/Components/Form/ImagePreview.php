<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImagePreview extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $label = 'Ảnh xem trước:',
        public ?string $src = null,
        public int $width = 120
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.image-preview');
    }
}
