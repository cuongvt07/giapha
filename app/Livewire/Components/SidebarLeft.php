<?php

namespace App\Livewire\Components;

use Livewire\Component;

class SidebarLeft extends Component
{
    public $showAlive = true;
    public $showDeceased = true;
    public $showMale = true;
    public $showFemale = true;
    
    // Display Settings
    public $showDates = true;
    public $showTitles = true;
    public $showSpouses = true;
    public $treeTitle = 'Gia phả dòng họ Nguyễn - Kính dâng tổ tiên';

    public $search = '';

    public function updated()
    {
        $this->dispatch('filters-updated', [
            'showAlive' => $this->showAlive,
            'showDeceased' => $this->showDeceased,
            'showMale' => $this->showMale,
            'showFemale' => $this->showFemale,
            'showDates' => $this->showDates,
            'showTitles' => $this->showTitles,
            'showSpouses' => $this->showSpouses,
            'treeTitle' => $this->treeTitle,
        ]);
    }

    public function selectPerson($id)
    {
        // Focus on the selected person from search
        $this->dispatch('focus-on-branch', ['personId' => $id]);
        $this->search = ''; // Clear search after selection
    }

    public function render()
    {
        $stats = [
            'total_members' => \App\Models\Person::count(),
            'living_members' => \App\Models\Person::where('is_alive', true)->count(),
            'deceased_members' => \App\Models\Person::where('is_alive', false)->count(),
            'total_generations' => \App\Models\Person::whereNotNull('generation_id')->distinct('generation_id')->count('generation_id'),
            'male_members' => \App\Models\Person::where('gender', 'male')->count(),
            'female_members' => \App\Models\Person::where('gender', 'female')->count(),
        ];

        $searchResults = [];
        if (strlen($this->search) >= 2) {
            $searchResults = \App\Models\Person::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('nickname', 'like', '%' . $this->search . '%')
                ->take(10)
                ->get();
        }

        return view('livewire.components.sidebar-left', [
            'stats' => $stats,
            'searchResults' => $searchResults,
        ]);
    }
}
