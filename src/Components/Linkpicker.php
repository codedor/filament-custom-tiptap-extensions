<?php

namespace Codedor\FilamentCustomTiptapExtensions\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Linkpicker extends Component
{
    public function __construct(
        public string $statePath
    ) {}

    public function render(): View|Closure|string
    {
        return view('filament-custom-tiptap-extensions::components.linkpicker');
    }
}
