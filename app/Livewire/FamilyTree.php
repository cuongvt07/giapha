<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Person;

class FamilyTree extends Component
{
    public $rootPerson;
    public $focusedPersonId = null;
    public $originalRootId = null;
    public $breadcrumbPath = [];
    public $treeVersion = 0;
    
    // Tab & Search Logic for Mobile Menu
    public $activeTab = 'tree'; 
    public $listSearch = '';
    public $mobileMenuOpen = false; // Control loop via mobile menu

    public $filters = [
        'showAlive' => true,
        'showDeceased' => true,
        'showMale' => true,
        'showFemale' => true,
        'showDates' => true,
        'showTitles' => true,
        'showSpouses' => true,
        'treeTitle' => 'Gia đình ông Làng, bà Oanh - Kính dâng tặng',
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openMenu()
    {
        $this->mobileMenuOpen = true;
    }

    public function closeMenu()
    {
        $this->mobileMenuOpen = false;
    }

    protected $listeners = [
        // 'refreshTree' => '$refresh', // Disabled to prevent flickering
        'filters-updated' => 'updateFilters',
        'focus-on-branch' => 'focusOnPerson',
        'tree-entity-saved' => 'onTreeEntitySaved',
    ];

    public function onTreeEntitySaved($personId)
    {
        // Refresh root to ensure fresh data (especially if children relation was cached)
        if ($this->rootPerson) {
             $this->rootPerson->refresh();
        } else {
            // If no root existed, find the one that was just created
            $originalRoot = Person::whereNull('father_id')
                ->whereNull('mother_id')
                ->first();

            if ($originalRoot) {
                $this->originalRootId = $originalRoot->id;
                $this->rootPerson = $originalRoot;
            }
        }
        
        $this->treeVersion++;
        
        // Dispatch browser event to center on the new/updated node
        $this->dispatch('center-on-node', nodeId: 'node-' . $personId);
    }

    public function updateFilters($filters)
    {
        $this->filters = array_merge($this->filters, $filters);
    }
    
    public function mount()
    {
        // Mobile Redirect Logic Removed (Unified View)

        // Get the original root person
        $originalRoot = Person::whereNull('father_id')
            ->whereNull('mother_id')
            ->first();

        if ($originalRoot) {
            $this->originalRootId = $originalRoot->id;
            $this->rootPerson = $originalRoot;
        }
    }

    public function focusOnPerson($personId)
    {
        $this->focusedPersonId = $personId;
        
        // Load the focused person as the NEW ROOT - only load descendants, not ancestors
        // This makes them the "top" of the tree
        // Load the focused person as the NEW ROOT - only load descendants, not ancestors
        // This makes them the "top" of the tree
        $focusedPerson = Person::find($personId);

        if ($focusedPerson) {
            $this->rootPerson = $focusedPerson;
            $this->breadcrumbPath = $focusedPerson->getAncestorPath();
            
            // Dispatch event to frontend to re-center view
            $this->dispatch('tree-focused', ['personId' => $personId]);
        }
    }

    public function resetToRoot()
    {
        $this->focusedPersonId = null;
        $this->breadcrumbPath = [];
        
        // Reload original root
        if ($this->originalRootId) {
            $this->rootPerson = Person::find($this->originalRootId);
        }

        // Dispatch event to frontend to re-center view
        $this->dispatch('tree-reset');
    }


    public function selectPerson($personId)
    {
        $this->dispatch('person-selected', id: $personId);
    }

    public function deletePerson($personId)
    {
        $person = Person::find($personId);
        if ($person) {
            // Check if deleting current root
            if ($this->rootPerson && $this->rootPerson->id == $personId) {
                $this->resetToRoot();
            }
            
            // Delete person (database should handle cascades or orphans)
            $person->delete();
            
            // Refresh logic handled by Livewire re-render
            // Optionally notify user
        }
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

        return view('livewire.family-tree', [
            'stats' => $stats,
            'members' => $members
        ])->layout('components.layouts.app-canvas');
    }
}
