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

    protected $listeners = [
        // 'refreshTree' => '$refresh', // Disabled to prevent flickering
        'filters-updated' => 'updateFilters',
        'focus-on-branch' => 'focusOnPerson',
    ];

    public function updateFilters($filters)
    {
        $this->filters = array_merge($this->filters, $filters);
    }
    
    public function mount()
    {
        // Get the original root person
        $originalRoot = Person::with([
            'children',
            'children.children',
            'children.children.children',
            'children.children.children.children',
            'children.children.children.children.children'
        ])
            ->whereNull('father_id')
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
        $focusedPerson = Person::with([
            'children',
            'children.children',
            'children.children.children',
            'children.children.children.children',
            'children.children.children.children.children'
        ])->find($personId);

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
            $this->rootPerson = Person::with([
                'children',
                'children.children',
                'children.children.children',
                'children.children.children.children',
                'children.children.children.children.children'
            ])->find($this->originalRootId);
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
        return view('livewire.family-tree')
            ->layout('components.layouts.app-canvas');
    }
}
