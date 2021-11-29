<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\Suggestion;

class SuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Suggestion::create([
            'suggestion'=>'Great Ride!',
            'type'=>'review_by_user',
            'category'=>'positive_review',
        ]);

        Suggestion::create([
            'suggestion'=>'Smooth Ride!',
            'type'=>'review_by_user',
            'category'=>'positive_review',
        ]);


        Suggestion::create([
            'suggestion'=>'Trash Vehicle!',
            'type'=>'review_by_user',
            'category'=>'negative_review',
        ]);

        Suggestion::create([
            'suggestion'=>'Drunk Customer!',
            'type'=>'review_by_rider',
            'category'=>'negative_review',
        ]);
        
        Suggestion::create([
            'suggestion'=>'Great Customer!',
            'type'=>'review_by_rider',
            'category'=>'positive_review',
        ]);

        Suggestion::create([
            'suggestion'=>'Good Customer!',
            'type'=>'review_by_rider',
            'category'=>'positive_review',
        ]);


        Suggestion::create([
            'suggestion'=>'Rider was too Late!',
            'type'=>'booking_cancel_by_user',
            'category'=>'',
        ]);

        Suggestion::create([
            'suggestion'=>'Customer was unreachable!',
            'type'=>'booking_cancel_by_rider',
            'category'=>'',
        ]);

    }
}
