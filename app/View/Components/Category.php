<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Category extends Component
{
    public $vendor;
    public $categories;
    public $vendorcategory;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($categories, $vendorcategory, $vendor)
    {
        $this->vendor = $vendor;
        $this->categories = $categories;
        $this->vendorcategory = $vendorcategory;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.category');
    }
}
