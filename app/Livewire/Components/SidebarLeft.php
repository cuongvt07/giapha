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

    public $activeTab = 'tree'; // 'tree', 'stats', 'list'
    public $listSearch = ''; // Search for the list tab
    public $mobileMenuOpen = false;

    protected $listeners = ['toggle-left-sidebar' => 'openMenu'];

    public function openMenu()
    {
        $this->mobileMenuOpen = true;
    }

    public function closeMenu()
    {
        $this->mobileMenuOpen = false;
    }

    public function openAddModal()
    {
        $this->dispatch('open-add-modal'); // Dispatches to SidebarRight to open Add Form
        $this->closeMenu();
    }

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

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function selectPerson($id)
    {
        // 1. Open Details (Sidebar Right listens to this)
        $this->dispatch('person-selected', $id);

        // 2. Center on Node (Browser JS listens to this)
        $this->dispatch('center-on-node', nodeId: 'node-' . $id);
    }

    public function render()
    {
        $stats = [];
        $members = [];

        if ($this->activeTab === 'stats') {
            $stats = [
                'total_members' => \App\Models\Person::count(),
                'living_members' => \App\Models\Person::where('is_alive', true)->count(),
                'deceased_members' => \App\Models\Person::where('is_alive', false)->count(),
                'total_generations' => \App\Models\Person::whereNotNull('generation_id')->distinct('generation_id')->count('generation_id'),
                'male_members' => \App\Models\Person::where('gender', 'male')->count(),
                'female_members' => \App\Models\Person::where('gender', 'female')->count(),
            ];
        }

        if ($this->activeTab === 'list') {
            $query = \App\Models\Person::query();
            if ($this->listSearch) {
                $query->where('name', 'like', '%' . $this->listSearch . '%')
                      ->orWhere('nickname', 'like', '%' . $this->listSearch . '%');
            }
            $members = $query->orderBy('name')->simplePaginate(15);
        }

        return view('livewire.components.sidebar-left', [
            'stats' => $stats,
            'members' => $members,
        ]);
    }
}
