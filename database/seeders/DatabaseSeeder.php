<?php

namespace Database\Seeders;

use App\Models\Constants\PostStatus;
use App\Models\Constants\Reaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Simple seed constants.
         */
        $constantData = [
            PostStatus::class => [
                ['code' => 'DRAFT'],
                ['code' => 'PUBLISHED']
            ],
            Reaction::class => [
                ['code' => 'LIKE']
            ]
        ];
        foreach ($constantData as $class => $items) {
            foreach ($items as $itemData) {
                $class::query()->updateOrCreate($itemData);
            }
        }
    }
}
