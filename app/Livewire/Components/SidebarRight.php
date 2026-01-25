<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Person;
use Illuminate\Support\Facades\Log;

class SidebarRight extends Component
{
    use WithFileUploads;

    public $personId;
    public $person;
    // Mode: view, edit, add
    public $mode = 'view';
    
    // Sidebar & Modal State
    public $detailsOpen = false; // Controls Sidebar Visibility
    public $viewState = 'tools'; // 'tools', 'details', 'form'

    // Search Logic
    public $search = '';
    public $results = [];
    public $fromSearch = false; // Track navigation source


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
    public $facebook_url;
    public $burial_place;
    public $burial_date;
    public $birth_date_full; 
    public $death_date_full;
    public $grave_photo;
    public $existing_grave_photo_url;

    // List Data
    public $biographyList = [];
    public $achievementList = [];



    // Validation Rules
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'death_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'is_alive' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'avatar' => 'nullable|image|max:2048',
            'nickname' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'hometown' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'family_branch_id' => 'nullable|exists:family_branches,id',
            'biographyList.*.content' => 'nullable|string',
            'biographyList.*.time_period' => 'nullable|string',
            'achievementList.*.title' => 'nullable|string',
            'achievementList.*.time_period' => 'nullable|string',
        ];
    }

    #[On('person-selected')]
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
            'biographyEntries',
            'marriagesAsHusband.wife', 
            'marriagesAsWife.husband'
        ])->find($this->personId);
        $this->viewState = 'details';
        $this->activeTab = 'info';
        $this->detailsOpen = true;
        $this->fromSearch = false; // Opened from Tree
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addMoreBiographies()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->biographyList[] = ['id' => null, 'content' => '', 'time_period' => ''];
        }
    }

    public function addMoreAchievements()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->achievementList[] = ['id' => null, 'title' => '', 'time_period' => ''];
        }
    }

    public function startEditing($tab = 'info')
    {
        $this->resetValidation();
        $this->mode = 'edit';
        $this->viewState = 'form';
        $this->activeTab = $tab;
        
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
            $this->facebook_url = $this->person->facebook_url;
            
            // Burial
            if ($this->person->burialInfo) {
                $this->burial_place = $this->person->burialInfo->burial_place;
                $this->burial_date = $this->person->burialInfo->burial_date;
                $this->death_date_full = $this->person->burialInfo->death_date_full;
                $this->existing_grave_photo_url = $this->person->burialInfo->grave_photo_path;
            } else {
                $this->burial_place = null;
                $this->burial_date = null;
                $this->death_date_full = null;
                $this->existing_grave_photo_url = null;
            }
            $this->grave_photo = null;

            $this->existing_avatar_url = $this->person->avatar_url;
            $this->avatar = null;

            // Load Lists
            $this->biographyList = [];
            foreach ($this->person->biographyEntries as $entry) {
                $this->biographyList[] = [
                    'id' => $entry->id,
                    'content' => $entry->content,
                    'time_period' => $entry->time_period
                ];
            }
            // Always ensure minimum buffer of empty slots (or just add 10 if < 10? No, simply fill up to decent amount or append 10 empty)
            // User strategy: "cứ để sẵn tầm 10 cái" => "Just have about 10 ready"
            // If we have 3, add 7? Or just add 10 empty ones at end. Let's add 10 empty at end for easy adding.
            for ($i = 0; $i < 10; $i++) {
                $this->biographyList[] = ['id' => null, 'content' => '', 'time_period' => ''];
            }

            $this->achievementList = [];
            foreach ($this->person->achievements as $ach) {
                $this->achievementList[] = [
                    'id' => $ach->id,
                    'title' => $ach->title,
                    'time_period' => $ach->time_period
                ];
            }
            for ($i = 0; $i < 10; $i++) {
                $this->achievementList[] = ['id' => null, 'title' => '', 'time_period' => ''];
            }
        }
    }

    #[On('open-add-modal')]
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
        
        $this->biographyList = [];
        for ($i = 0; $i < 10; $i++) {
            $this->biographyList[] = ['id' => null, 'content' => '', 'time_period' => ''];
        }

        $this->achievementList = [];
        for ($i = 0; $i < 10; $i++) {
            $this->achievementList[] = ['id' => null, 'title' => '', 'time_period' => ''];
        }
        
        $this->detailsOpen = true;
    }

    public function cancel()
    {
        // Detect Mobile to force close (since Search is hidden on mobile)
        $userAgent = strtolower(request()->header('User-Agent'));
        $isMobile = preg_match('/(android|webos|iphone|ipad|ipod|blackberry|windows phone)/i', $userAgent);

        if ($isMobile) {
            $this->detailsOpen = false;
            // Reset to tools silently so next open starts fresh if needed, or keep details? 
            // Better to keep details in case they reopen? No, close is close.
            return;
        }

        if ($this->viewState === 'form') {
            if ($this->mode === 'add') {
                if ($this->personId) {
                    $this->mode = 'view';
                    $this->viewState = 'details';
                    // Reload original person to ensure state is clean
                    $this->loadPerson($this->personId);
                } else {
                     // If adding from root/scratch
                     if ($this->fromSearch) {
                         $this->viewState = 'tools';
                     } else {
                         $this->detailsOpen = false;
                     }
                }
            } else {
                $this->mode = 'view';
                $this->viewState = 'details';
            }
        } elseif ($this->viewState === 'details') {
            if ($this->fromSearch) {
                $this->viewState = 'tools';
                $this->mode = 'view';
                $this->person = null;
            } else {
                $this->detailsOpen = false;
            }
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
            $person->facebook_url = $this->facebook_url;

            $person->facebook_url = $this->facebook_url;

            if ($this->avatar) {
                $path = $this->avatar->store('avatars', 'public');
                $person->avatar_path = $path;
            }

            $person->save();

            $debugMsg = 'Avatar: ' . ($person->avatar_path ?? 'null');

            // Update Burial Info
            if (!$this->is_alive && ($this->burial_place || $this->burial_date || $this->death_date_full || $this->grave_photo)) {
                $burialData = [
                    'burial_place' => $this->burial_place,
                    'burial_date' => $this->burial_date,
                    'death_date_full' => $this->death_date_full,
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
            $this->saveLists($person);
            
            $this->loadPerson($this->personId);
            $this->dispatch('tree-entity-saved', personId: $this->personId)->to('family-tree');
        }
    }

    protected function saveLists($person) 
    {
        // Save Biographies
        foreach ($this->biographyList as $index => $item) {
            if (!empty($item['content']) || !empty($item['time_period'])) {
                if ($item['id']) {
                    $person->biographyEntries()->updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'content' => $item['content'],
                            'time_period' => $item['time_period'],
                            'display_order' => $index
                        ]
                    );
                } else {
                    $person->biographyEntries()->create([
                        'content' => $item['content'],
                        'time_period' => $item['time_period'],
                        'display_order' => $index
                    ]);
                }
            } else {
                if ($item['id']) {
                    \App\Models\BiographyEntry::destroy($item['id']);
                }
            }
        }

        // Save Achievements
        foreach ($this->achievementList as $index => $item) {
             if (!empty($item['title']) || !empty($item['time_period'])) {
                if ($item['id']) {
                    $person->achievements()->updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'title' => $item['title'],
                            'time_period' => $item['time_period'],
                            'achievement_type' => 'other', // Default
                            'display_order' => $index
                        ]
                    );
                } else {
                     $person->achievements()->create([
                        'title' => $item['title'],
                        'time_period' => $item['time_period'],
                        'achievement_type' => 'other',
                        'display_order' => $index
                    ]);
                }
            } else {
                if ($item['id']) {
                    \App\Models\Achievement::destroy($item['id']);
                }
            }
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
        $newPerson->title = $this->title;

        if ($this->is_alive) {
            $newPerson->death_year = null;
        } else {
            $newPerson->death_year = $this->death_year;
        }
        
        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $newPerson->avatar_path = $path;
        }

        if ($parent) {
            if ($this->relationship_type === 'child') {
                if ($parent->gender === 'male') {
                    $newPerson->father_id = $parent->id;
                } else {
                    $newPerson->mother_id = $parent->id;
                }
            } elseif ($this->relationship_type === 'spouse') {
                // Legacy fallback
                $newPerson->spouse_id = $parent->id;
            }
        }
        
        // Save the person FIRST so we have an ID
        $newPerson->save();
        
        // NOW create Marriage record (after newPerson has an ID)
        if ($parent && $this->relationship_type === 'spouse') {
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
        }
        
        // Save extra fields

             $newPerson->nickname = $this->nickname;
             $newPerson->place_of_birth = $this->place_of_birth;
             $newPerson->hometown = $this->hometown;
             $newPerson->occupation = $this->occupation;

             $newPerson->family_branch_id = $this->family_branch_id;
             $newPerson->address = $this->address;
             $newPerson->phone = $this->phone;
             $newPerson->email = $this->email;
             $newPerson->facebook_url = $this->facebook_url;
             $newPerson->save();


        // Burial Info for New Person
        if (!$this->is_alive && ($this->burial_place || $this->burial_date || $this->death_date_full || $this->grave_photo)) {
            $burialData = [
                'burial_place' => $this->burial_place,
                'burial_date' => $this->burial_date,
                'death_date_full' => $this->death_date_full,
            ];

            if ($this->grave_photo) {
                $path = $this->grave_photo->store('burial_photos', 'public');
                $burialData['grave_photo_path'] = '/storage/' . $path;
            }

            $newPerson->burialInfo()->create($burialData);
        }

        $this->saveLists($newPerson);

        $this->loadPerson($newPerson->id);
        $this->dispatch('tree-entity-saved', personId: $newPerson->id)->to('family-tree');
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

    #[On('toggle-sidebar')]
    public function toggle()
    {
        $this->detailsOpen = !$this->detailsOpen;
    }

    public function performSearch()
    {
        if (strlen($this->search) >= 1) {
            $this->results = Person::where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('nickname', 'like', '%' . $this->search . '%')
                            ->take(15)
                            ->get();
        } else {
            $this->results = [];
        }
    }

    public function selectResult($id)
    {
        // 1. Indicate navigation from search
        $this->fromSearch = true;

        // 2. Load details
        $this->loadPerson($id);
        
        // 3. Dispatch event to Javascript to center the node
        $this->dispatch('center-on-node', nodeId: 'node-' . $id);
    }
    
    public function render()
    {
        $branches = \App\Models\FamilyBranch::all();
        
        // Results are now populated by performSearch(), not automatically in render
        // But if viewState is 'tools' and we have results from previous search, they persist.
        // We don't need to do anything here.

        return view('livewire.components.sidebar-right', [
            'branches' => $branches,
            // 'results' passed via property
        ]);
    }
}
