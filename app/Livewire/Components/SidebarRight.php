<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Person;

class SidebarRight extends Component
{
    use WithFileUploads;

    public $personId;
    public $person;
    // Mode: view, edit, add
    public $mode = 'view';
    
    // Sidebar & Modal State
    public $detailsOpen = true; // Controls Sidebar Visibility
    public $viewState = 'tools'; // 'tools', 'details', 'form'

    // Search Logic
    public $search = '';
    public $results = [];

    // Form Fields
    public $name;
    public $gender;
    public $birth_year;
    public $death_year;
    public $is_alive = true;
    public $title;
    public $order;
    public $avatar;
    public $existing_avatar_url;
    public $parentId;
    public $relationship_type = 'child';
    public $marriage_type_input = 'chinh_thuc'; // For form input
    public $family_branch_id; // For Branch Selection

    // Tabs
    public $activeTab = 'info'; // info, bio, burial, achievements

    // Expanded Fields
    public $nickname;
    public $place_of_birth;
    public $hometown;
    public $occupation;
    public $address;
    public $phone;
    public $email;
    public $burial_place;
    public $burial_date;
    public $birth_date_full; 
    public $death_date_full;
    public $grave_photo;
    public $existing_grave_photo_url;


    // ... (rest of properties)

    protected $listeners = ['person-selected' => 'loadPerson', 'open-add-modal' => 'startAdding', 'toggle-sidebar' => 'toggle'];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->results = Person::where('name', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function selectResult($id)
    {
        $this->loadPerson($id);
        $this->search = ''; 
        $this->results = [];
    }

    // ... rules ...

    public function loadPerson($id)
    {
        $this->resetValidation();
        $this->mode = 'view';
        $this->personId = $id;
        $this->person = Person::with([
            'father', 
            'mother', 
            'children', 
            'burialInfo', 
            'achievements',
            'marriagesAsHusband.wife', 
            'marriagesAsWife.husband'
        ])->find($this->personId);
        $this->viewState = 'details';
        $this->activeTab = 'info';
        $this->detailsOpen = true;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function startEditing()
    {
        $this->resetValidation();
        $this->mode = 'edit';
        $this->viewState = 'form';
        
        if ($this->person) {
            $this->name = $this->person->name;
            $this->gender = $this->person->gender;
            $this->birth_year = $this->person->birth_year;
            $this->death_year = $this->person->death_year;
            $this->is_alive = (bool)$this->person->is_alive;
            $this->title = $this->person->title;
            $this->order = $this->person->order;
            
            // New Fields
            $this->nickname = $this->person->nickname;
            $this->place_of_birth = $this->person->place_of_birth;
            $this->hometown = $this->person->hometown;
            $this->occupation = $this->person->occupation;
            $this->family_branch_id = $this->person->family_branch_id;
            $this->address = $this->person->address;
            $this->phone = $this->person->phone;
            $this->email = $this->person->email;
            
            // Burial
            if ($this->person->burialInfo) {
                $this->burial_place = $this->person->burialInfo->burial_place;
                $this->burial_date = $this->person->burialInfo->burial_date;
                $this->existing_grave_photo_url = $this->person->burialInfo->grave_photo_path;
            } else {
                $this->burial_place = null;
                $this->burial_date = null;
                $this->existing_grave_photo_url = null;
            }
            $this->grave_photo = null;

            $this->existing_avatar_url = $this->person->avatar_url;
            $this->avatar = null;
        }
    }

    public function startAdding($data = [])
    {
        $this->resetValidation();
        $this->mode = 'add';
        $this->viewState = 'form';
        $this->parentId = $data['parentId'] ?? null;
        
        // Default values
        $this->reset(['name', 'birth_year', 'avatar', 'title', 'order', 'death_year', 'grave_photo']);
        $this->gender = 'male';
        $this->is_alive = 1;
        $this->relationship_type = 'child';
        $this->marriage_type_input = 'chinh_thuc'; // Default
        $this->family_branch_id = null;
        $this->existing_avatar_url = null;
        
        $this->detailsOpen = true;
    }

    public function cancel()
    {
        if ($this->viewState === 'form') {
            if ($this->mode === 'add') {
                if ($this->personId) {
                    $this->mode = 'view';
                    $this->viewState = 'details';
                    // Reload original person to ensure state is clean
                    $this->loadPerson($this->personId);
                } else {
                    $this->viewState = 'tools';
                }
            } else {
                $this->mode = 'view';
                $this->viewState = 'details';
            }
        } elseif ($this->viewState === 'details') {
            $this->viewState = 'tools';
            $this->mode = 'view';
            $this->person = null;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->mode === 'edit') {
            $this->updatePerson();
        } elseif ($this->mode === 'add') {
            $this->createPerson();
        }

        // Return to details view after save
        $this->mode = 'view';
        $this->viewState = 'details'; 
        // Note: loadPerson is called at end of updatePerson/createPerson usually, 
        // make sure createPerson sets personId correctly if directing to details
    }

    protected function updatePerson()
    {
        $person = Person::find($this->personId);
        if ($person) {
            $person->name = $this->name;
            $person->gender = $this->gender;
            $person->birth_year = $this->birth_year;
            $person->is_alive = (bool)$this->is_alive;
            $person->order = $this->order;
            $person->title = $this->title;

            if ($this->is_alive) {
                $person->death_year = null;
            } else {
                $person->death_year = $this->death_year;
            }

            // Save new fields
            $person->nickname = $this->nickname;
            $person->place_of_birth = $this->place_of_birth;
            $person->hometown = $this->hometown;
            $person->occupation = $this->occupation;
            $person->family_branch_id = $this->family_branch_id;
            $person->address = $this->address;
            $person->phone = $this->phone;
            $person->email = $this->email;


            if ($this->avatar) {
                $path = $this->avatar->store('avatars', 'public');
                $person->avatar_url = '/storage/' . $path;
            }

            $person->save();

            // Update Burial Info
            if (!$this->is_alive && ($this->burial_place || $this->burial_date || $this->grave_photo)) {
                $burialData = [
                    'burial_place' => $this->burial_place,
                    'burial_date' => $this->burial_date,
                ];

                if ($this->grave_photo) {
                    $path = $this->grave_photo->store('burial_photos', 'public');
                    $burialData['grave_photo_path'] = '/storage/' . $path;
                }

                $person->burialInfo()->updateOrCreate(
                    ['person_id' => $person->id],
                    $burialData
                );
            }

            // Reload to show updated info
            $this->loadPerson($this->personId);
            $this->dispatch('refreshTree')->to('family-tree');
        }
    }

    protected function createPerson()
    {
        $parent = $this->parentId ? Person::find($this->parentId) : null;
        
        $newPerson = new Person();
        $newPerson->name = $this->name;
        $newPerson->gender = $this->gender;
        $newPerson->birth_year = $this->birth_year;
        $newPerson->is_alive = (bool)$this->is_alive;
        $newPerson->order = $this->order ?: 1; 
        
        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $newPerson->avatar_url = '/storage/' . $path;
        }

        if ($parent) {
            if ($this->relationship_type === 'child') {
                if ($parent->gender === 'male') {
                    $newPerson->father_id = $parent->id;
                } else {
                    $newPerson->mother_id = $parent->id;
                }
                $newPerson->generation = $parent->generation + 1;
            } elseif ($this->relationship_type === 'spouse') {
                // Create Marriage Record
                $marriage = new \App\Models\Marriage();
                if ($parent->gender === 'male') {
                    $marriage->husband_id = $parent->id;
                    $marriage->wife_id = $newPerson->id;
                } else {
                    $marriage->husband_id = $newPerson->id;
                    $marriage->wife_id = $parent->id;
                }
                
                $marriage->marriage_type = $this->marriage_type_input;
                $marriage->save();

                // Legacy fallback (optional, but keep for simplicity if needed anywhere)
                $newPerson->spouse_id = $parent->id;
                
                $newPerson->generation = $parent->generation;
            }
        } else {
            // Creating Root Person
            $newPerson->generation = 1;
        }
        
        $newPerson->save();
        
        // Save extra fields immediately if set
        if ($this->nickname || $this->place_of_birth || $this->hometown || $this->occupation || $this->family_branch_id) {
             $newPerson->nickname = $this->nickname;
             $newPerson->place_of_birth = $this->place_of_birth;
             $newPerson->hometown = $this->hometown;
             $newPerson->occupation = $this->occupation;
             $newPerson->family_branch_id = $this->family_branch_id;
             $newPerson->save();
        }

        $this->loadPerson($newPerson->id);
        $this->dispatch('refreshTree')->to('family-tree');
    }

    public function deletePerson()
    {
        if ($this->personId) {
            Person::destroy($this->personId);
            $this->person = null;
            $this->personId = null;
            $this->detailsOpen = false;
            $this->dispatch('refreshTree')->to('family-tree');
        }
    }

    public function toggle()
    {
        $this->detailsOpen = !$this->detailsOpen;
    }

    public function render()
    {
        $branches = \App\Models\FamilyBranch::all(); // Retrieve branches
        $results = [];

        if ($this->viewState === 'tools') {
            if (strlen($this->search) >= 2) {
                $results = Person::where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('nickname', 'like', '%' . $this->search . '%')
                                ->take(10)
                                ->get();
            }
        }

        return view('livewire.components.sidebar-right', [
            'branches' => $branches,
            'results' => $results,
        ]);
    }
}
