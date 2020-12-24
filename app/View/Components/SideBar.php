<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SideBar extends Component
{
    /**
     * The sudents of a class
     *
     * @var collect
     */
    public $students;
    /**
     * The class
     *
     * @var classe
     */
    public $class;
    /**
     * This week assignments
     *
     * @var collect
     */
    public $tw_assignments;
    /**
     * Next week assignments
     *
     * @var collect
     */
    public $nw_assignments;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($students, $class, $twassignments, $nwassignments)
    {
        $this->students = $students;
        $this->class = $class;
        $this->tw_assignments = $twassignments;
        $this->nw_assignments = $nwassignments;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.side-bar');
    }
}
