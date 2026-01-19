<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Person;

class MobileFamilyTree extends Component
{
    // Tree Data
    public $rootPerson;
    public $focusedPersonId = null;
    public $treeVersion = 0;
    public $originalRootId = null;

    // UI States
    public $showMenu = false;
    public $showBottomSheet = false;
    public $selectedPerson = null;
    public $showAddModal = false;
    public $addParentId = null;

    // Filters
    public $filters = [
        'showAlive' => true,
        'showDeceased' => true,
        'showMale' => true,
        'showFemale' => true,
        'showDates' => true,
        'showSpouses' => true,
    ];

    public function mount()
    {
        $this->loadRootPerson();
    }

    protected function loadRootPerson()
    {
        $root = Person::with([
            'children',
            'children.children',
            'children.children.children',
            'children.children.children.children',
            'children.children.children.children.children'
        ])
            ->whereNull('father_id')
            ->whereNull('mother_id')
            ->first();

        if ($root) {
            $this->originalRootId = $root->id;
            $this->rootPerson = $root;
        }
    }

    // Menu Actions
    public function toggleMenu()
    {
        $this->showMenu = !$this->showMenu;
    }

    public function closeMenu()
    {
        $this->showMenu = false;
    }

    // Person Selection
    public function selectPerson($personId)
    {
        $this->selectedPerson = Person::with([
            'father',
            'mother',
            'children',
            'burialInfo',
            'achievements',
            'marriagesAsHusband.wife',
            'marriagesAsWife.husband'
        ])->find($personId);
        
        $this->showBottomSheet = true;
    }

    public function closeBottomSheet()
    {
        $this->showBottomSheet = false;
        $this->selectedPerson = null;
    }

    // Focus on branch
    public function focusOnPerson($personId)
    {
        $this->focusedPersonId = $personId;
        
        $focusedPerson = Person::with([
            'children',
            'children.children',
            'children.children.children',
            'children.children.children.children',
        ])->find($personId);

        if ($focusedPerson) {
            $this->rootPerson = $focusedPerson;
            $this->treeVersion++;
        }
        
        $this->closeBottomSheet();
        $this->dispatch('tree-updated');
    }

    public function resetToRoot()
    {
        $this->focusedPersonId = null;
        
        if ($this->originalRootId) {
            $this->loadRootPerson();
            $this->treeVersion++;
            $this->dispatch('tree-updated');
        }
    }

    // Add Person
    public function openAddModal($parentId = null)
    {
        $this->addParentId = $parentId;
        $this->showAddModal = true;
        $this->closeBottomSheet();
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->addParentId = null;
    }

    public function deletePerson($personId)
    {
        $person = Person::find($personId);
        if ($person) {
            $person->delete();
            $this->closeBottomSheet();
            
            if ($this->rootPerson && $this->rootPerson->id == $personId) {
                $this->resetToRoot();
            } else {
                 $this->loadRootPerson(); // Refresh tree
                 $this->treeVersion++;
                 $this->dispatch('tree-updated');
            }
        }
    }

    // Filter updates
    public function updateFilter($key, $value)
    {
        $this->filters[$key] = $value;
        $this->treeVersion++;
        $this->dispatch('tree-updated');
    }

    public function render()
    {
        return view('livewire.mobile-family-tree')
            ->layout('components.layouts.mobile-layout');
    }
}
