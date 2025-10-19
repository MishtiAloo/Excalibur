<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{SearchGroup, Volunteer};

class GroupMemberSeeder extends Seeder
{
    public function run(): void
    {
        $groups = SearchGroup::all();
        $volunteers = Volunteer::all();

        if ($groups->isEmpty() || $volunteers->isEmpty()) {
            return; // nothing to do
        }

        foreach ($groups as $g) {
            // attach up to 2 volunteers per group
            $ids = $volunteers->random(min(2, $volunteers->count()))->pluck('volunteer_id')->toArray();
            $g->volunteers()->syncWithoutDetaching($ids);
        }
    }
}
