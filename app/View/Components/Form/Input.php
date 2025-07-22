<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $label,
        public string $type = 'text',
        public mixed $value = '',
        public bool $required = false
    ) {}

    public function render()
    {
        return view('components.form.input');
    }
}
