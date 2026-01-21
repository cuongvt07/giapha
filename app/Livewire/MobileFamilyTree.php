<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Person;

class MobileFamilyTree extends Component
{
    use WithFileUploads;
    // Tree Data
    public $rootPerson;
    public $focusedPersonId = null;
    public $treeVersion = 0;
    public $originalRootId = null;

    // UI States
    public $showMenu = false;
    public $modalMode = 'none'; // 'none', 'view', 'add', 'edit'
    public $selectedPerson = null;
    public $addParentId = null;
    public $editingPersonId = null;

    // ... (Fields remain same) ...

    // Modal Management
    public function openModal($personId, $mode = 'view')
    {
        $this->loading = true;
        $this->modalMode = $mode;

        if ($mode === 'view') {
           $this->selectPerson($personId);
        } elseif ($mode === 'add') {
            $this->openAddModal($personId); // Use personId as parentId
        } elseif ($mode === 'edit') {
            $this->editPerson($personId);
        }
        
        $this->loading = false;
    }

    public function closeModal()
    {
        $this->modalMode = 'none';
        $this->selectedPerson = null;
        $this->addParentId = null;
        $this->editingPersonId = null;
    }

    // Person Selection (View Mode)
    public function selectPerson($personId)
    {
        $this->modalMode = 'view';
        $this->selectedPerson = Person::with([
            'father',
            'mother',
            'children',
            'spouses',
            'burialInfo',
            'achievements',
            'marriagesAsHusband.wife',
            'marriagesAsWife.husband'
        ])->find($personId);
    }
    
    // Add Person Setup
    public function openAddModal($parentId = null)
    {
        $this->modalMode = 'add';
        $this->addParentId = $parentId;
        $this->editingPersonId = null;
        $this->reset(['newPersonName', 'newPersonGender', 'newPersonBirthYear', 'newPersonIsAlive', 'newPersonNickname', 'newPersonTitle', 'newPersonOccupation', 'newPersonHometown', 'newPersonPlaceOfBirth', 'newPersonAddress', 'newPersonPhone', 'newPersonEmail', 'newPersonDeathYear', 'newPersonDeathDateFull', 'newPersonBurialPlace', 'newPersonBurialDate', 'newPersonAvatar', 'newPersonGravePhoto', 'existingAvatarUrl', 'existingGravePhotoUrl']);
        $this->newPersonGender = 'male'; 
        $this->newPersonIsAlive = true;
    }

    // Edit Person Setup
    public function editPerson($personId)
    {
        $this->modalMode = 'edit';
        $person = Person::find($personId);
        if ($person) {
            $this->selectedPerson = $person; // Keep reference for header
            $this->editingPersonId = $person->id;
            $this->newPersonName = $person->name;
            $this->newPersonGender = $person->gender;
            $this->newPersonBirthYear = $person->birth_year;
            $this->newPersonIsAlive = (bool)$person->is_alive;
            
            // Fill extended fields
            $this->newPersonNickname = $person->nickname;
            $this->newPersonTitle = $person->title;
            // ... (rest of fields filling same as before) ...
            $this->newPersonOccupation = $person->occupation;
            $this->newPersonHometown = $person->hometown;
            $this->newPersonPlaceOfBirth = $person->place_of_birth;
            $this->newPersonAddress = $person->address;
            $this->newPersonPhone = $person->phone;
            $this->newPersonEmail = $person->email;
            $this->newPersonDeathYear = $person->death_year;
            
            // Files
            $this->existingAvatarUrl = $person->avatar_url;
            $this->newPersonAvatar = null;
            
            // Burial
            if ($person->burialInfo) {
                $this->newPersonBurialPlace = $person->burialInfo->burial_place;
                $this->newPersonBurialDate = $person->burialInfo->burial_date;
                $this->existingGravePhotoUrl = $person->burialInfo->grave_photo_path;
            } else {
                $this->newPersonBurialPlace = null;
                $this->newPersonBurialDate = null;
                $this->existingGravePhotoUrl = null;
            }
            $this->newPersonGravePhoto = null;
        }
    }

    public function savePerson()
    {
        $this->validate([
            'newPersonName' => 'required|string|max:255',
            'newPersonGender' => 'required|in:male,female',
            'newPersonBirthYear' => 'nullable|integer|min:1800|max:2030',
            'newPersonIsAlive' => 'boolean',
        ]);

        if ($this->editingPersonId) {
            $person = Person::find($this->editingPersonId);
            if (!$person) return;
        } else {
            $person = new Person();
            $person->order = 1;
        }

        $person->name = $this->newPersonName;
        $person->gender = $this->newPersonGender;
        $person->birth_year = $this->newPersonBirthYear;
        $person->is_alive = $this->newPersonIsAlive;
        
        // Extended fields
        $person->nickname = $this->newPersonNickname;
        $person->title = $this->newPersonTitle;
        $person->occupation = $this->newPersonOccupation;
        $person->hometown = $this->newPersonHometown;
        $person->place_of_birth = $this->newPersonPlaceOfBirth;
        $person->address = $this->newPersonAddress;
        $person->phone = $this->newPersonPhone;
        $person->email = $this->newPersonEmail;

        if ($this->newPersonIsAlive) {
            $person->death_year = null;
            $person->date_of_death = null;
        } else {
            $person->death_year = $this->newPersonDeathYear;
             // If full date is provided, it might override death_year logic in model, but for now specific mapping
             // Assuming model uses death_year for display mostly
        }
        
        // Avatar Upload
        if ($this->newPersonAvatar) {
            $path = $this->newPersonAvatar->store('avatars', 'public');
            $person->avatar_url = '/storage/' . $path;
        }

        // Relation logic only for new person
        if (!$this->editingPersonId && $this->addParentId) {
            $parent = Person::find($this->addParentId);
            if ($parent) {
                if ($parent->gender === 'male') {
                    $person->father_id = $parent->id;
                } else {
                    $person->mother_id = $parent->id;
                }
            }
        }

        $person->save();
        
        // Burial Info Saving
        if (!$this->newPersonIsAlive && ($this->newPersonBurialPlace || $this->newPersonBurialDate || $this->newPersonGravePhoto)) {
             $burialData = [
                'burial_place' => $this->newPersonBurialPlace,
                'burial_date' => $this->newPersonBurialDate,
            ];

            if ($this->newPersonGravePhoto) {
                $path = $this->newPersonGravePhoto->store('burial_photos', 'public');
                $burialData['grave_photo_path'] = '/storage/' . $path;
            }

            $person->burialInfo()->updateOrCreate(
                ['person_id' => $person->id],
                $burialData
            );
        }

        if ($this->editingPersonId) {
             $this->selectedPerson = $person->fresh(); // Update selected person view if open
        }

        $this->reset(['newPersonName', 'newPersonGender', 'newPersonBirthYear', 'newPersonIsAlive']);
        $this->modalMode = 'none'; // Close modal
        
        $this->loadRootPerson();
        $this->treeVersion++;
        $this->dispatch('tree-updated', focusNodeId: 'node-' . $person->id);
    }

    public function deletePerson($personId)
    {
        $person = Person::find($personId);
        if ($person) {
            $person->delete();
            $this->modalMode = 'none'; // Close modal
            
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
