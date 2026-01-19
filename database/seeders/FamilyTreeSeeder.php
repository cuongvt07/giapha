<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;

class FamilyTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds 100 people across 8 generations for a comprehensive family tree.
     */
    public function run(): void
    {
        // Load the family tree data
        $familyData = require database_path('seeders/data/family_tree_data.php');
        
        // Create a map to store old IDs to new Person IDs
        $idMap = [];
        
        // First pass: Create all persons without relationships
        foreach ($familyData as $personData) {
            $person = Person::create([
                'name' => $personData['name'],
                'gender' => $personData['gender'],
                'date_of_birth' => $personData['birth_year'] ? $personData['birth_year'] . '-01-01' : null,
                'date_of_death' => $personData['death_year'] ? $personData['death_year'] . '-01-01' : null,
                'is_alive' => is_null($personData['death_year']),
                'nickname' => $personData['notes'] ?? null,
            ]);
            
            // Store the mapping from data ID to actual Person ID
            $idMap[$personData['id']] = [
                'person_id' => $person->id,
                'parent_id' => $personData['parent_id'],
                'spouse_name' => $personData['spouse_name'],
                'gender' => $personData['gender'],
            ];
        }
        
        // Second pass: Update parent relationships
        foreach ($idMap as $dataId => $info) {
            if ($info['parent_id'] !== null) {
                $parentInfo = $idMap[$info['parent_id']];
                
                // Determine if parent is father or mother based on gender
                $updateData = [];
                if ($parentInfo['gender'] === 'male') {
                    $updateData['father_id'] = $parentInfo['person_id'];
                } else {
                    $updateData['mother_id'] = $parentInfo['person_id'];
                }
                
                Person::where('id', $info['person_id'])->update($updateData);
            }
        }
        
        $this->command->info('Successfully seeded ' . count($familyData) . ' people across 8 generations!');
        $this->command->info('Family tree structure:');
        $this->command->info('- Generation 1: 1 person (Root Ancestor)');
        $this->command->info('- Generation 2: 4 people');
        $this->command->info('- Generation 3: 8 people');
        $this->command->info('- Generation 4: 16 people');
        $this->command->info('- Generation 5: 20 people');
        $this->command->info('- Generation 6: 20 people');
        $this->command->info('- Generation 7: 21 people');
        $this->command->info('- Generation 8: 10 people (Current generation)');
    }
}
