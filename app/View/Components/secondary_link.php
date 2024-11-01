<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class secondary_link extends Component
{

    public $href;

    /**
     * Create a new component instance.
     */
    public function __construct($href = '')
    {
        $this->href = $href;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.secondary_link');
    }
}
