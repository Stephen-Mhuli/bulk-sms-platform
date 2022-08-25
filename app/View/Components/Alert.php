<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{

    public $type;

    public $message;

    public $isErrors;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($type,$message,$isErrors)
    {
        $this->type=$type;
        $this->message=$message;
        $this->isErrors=$isErrors;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
