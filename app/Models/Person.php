<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'father_id',
        'mother_id',
        'spouse_id',
        'family_branch_id',
        'generation_id',
        'root_ancestor_id',
        'order',
        'name',
        'nickname',
        'gender',
        'date_of_birth',
        'date_of_death',
        'is_alive',
        'biography',
        'place_of_birth',
        'hometown',
        'occupation',
        'title',
        'address',
        'phone',
        'email',
        'avatar_path',
        'lineage_position',
        'birth_order',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
        'is_alive' => 'boolean',
    ];

    public function father()
    {
        return $this->belongsTo(Person::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Person::class, 'mother_id');
    }

    public function spouse()
    {
        return $this->belongsTo(Person::class, 'spouse_id'); // Primary spouse shortcut
    }

    // New Relationships
    public function familyBranch()
    {
        return $this->belongsTo(FamilyBranch::class, 'family_branch_id');
    }

    public function generation()
    {
        return $this->belongsTo(Generation::class, 'generation_id');
    }

    public function rootAncestor()
    {
        return $this->belongsTo(Person::class, 'root_ancestor_id');
    }

    public function burialInfo()
    {
        return $this->hasOne(BurialInfo::class);
    }

    public function achievements()
    {
        return $this->hasMany(Achievement::class)->orderBy('display_order', 'asc');
    }

    public function customRelationships()
    {
        return $this->hasMany(Relationship::class, 'person_id');
    }

    // Multiple Spouses Logic
    public function marriagesAsHusband()
    {
        return $this->hasMany(Marriage::class, 'husband_id')->orderBy('marriage_order', 'asc');
    }

    public function marriagesAsWife()
    {
        return $this->hasMany(Marriage::class, 'wife_id')->orderBy('marriage_order', 'asc');
    }

    public function getSpousesAttribute()
    {
        // Dynamic accessor to get spouses from marriages table
        // plus potential legacy spouse logic if needed
        
        $spouses = collect();

        if ($this->gender === 'male') {
            $marriages = $this->marriagesAsHusband()->with('wife')->get();
            foreach ($marriages as $m) {
                if($m->wife) $spouses->push($m->wife);
            }
        } else {
            $marriages = $this->marriagesAsWife()->with('husband')->get();
            foreach ($marriages as $m) {
                if($m->husband) $spouses->push($m->husband);
            }
        }

        // Fallback or merged with simple spouse_id if migrating
        // For now, let's keep simplistic view: if spouses relation empty, check spouse_id
        if ($spouses->isEmpty() && $this->spouse_id) {
            $simpleSpouse = Person::find($this->spouse_id);
            if ($simpleSpouse) $spouses->push($simpleSpouse);
        }

        return $spouses->unique('id');
    }

    // Accessor to get children from EITHER father OR mother
    // This allows the tree to traverse down regardless of gender
    public function getChildrenAttribute()
    {
        return Person::where('father_id', $this->id)
                     ->orWhere('mother_id', $this->id)
                     ->orderBy('order', 'asc')
                     ->orderBy('date_of_birth', 'asc')
                     ->get();
    }

    // Keep strict relationship for eager loading compatibility
    // This prevents "undefined relationship" errors if with('children') is called
    public function children()
    {
        return $this->hasMany(Person::class, 'father_id');
    }

    // ... rest of accessors ...

    // Accessors & Mutators
    public function getBirthYearAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->year : null;
    }

    public function setBirthYearAttribute($value)
    {
        if ($value) {
            // Default to January 1st if only year is provided
            $this->attributes['date_of_birth'] = $value . '-01-01';
        } else {
            $this->attributes['date_of_birth'] = null;
        }
    }

    public function getDeathYearAttribute()
    {
        return $this->date_of_death ? $this->date_of_death->year : null;
    }

    public function setDeathYearAttribute($value)
    {
        if ($value) {
            $this->attributes['date_of_death'] = $value . '-01-01';
        } else {
            $this->attributes['date_of_death'] = null;
        }
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? asset('storage/' . $this->avatar_path) : null;
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    // Helper method to get ancestor path for breadcrumb navigation
    public function getAncestorPath()
    {
        $path = [];
        $current = $this;
        $depth = 0;
        $maxDepth = 20; // Safety limit

        while ($current && $depth < $maxDepth) {
            array_unshift($path, [
                'id' => $current->id,
                'name' => $current->name,
                'generation' => $current->generation_id,
            ]);

            // Move up to parent (prefer father, fallback to mother)
            $current = $current->father ?? $current->mother;
            $depth++;
        }

        return $path;
    }

    // Scope to load person with full lineage (ancestors and descendants)
    public function scopeWithFullLineage($query, $personId)
    {
        return $query->where('id', $personId)
            ->with([
                'father',
                'mother',
                'father.father',
                'father.mother',
                'mother.father',
                'mother.mother',
                // Children removed to use lazy loading via getChildrenAttribute
            ]);
    }

    // Get siblings (same father or mother)
    public function siblings()
    {
        return $this->hasMany(Person::class, 'father_id', 'father_id')
            ->where('id', '!=', $this->id)
            ->orderBy('order', 'asc')
            ->orderBy('date_of_birth', 'asc');
    }
}
