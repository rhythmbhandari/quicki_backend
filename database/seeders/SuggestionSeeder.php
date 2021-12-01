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
            'text'=>'Great Ride!',
            'type'=>'review_by_user',
            'category'=>'positive_review',
        ]);

        Suggestion::create([
            'text'=>'Smooth Ride!',
            'type'=>'review_by_user',
            'category'=>'positive_review',
        ]);


        Suggestion::create([
            'text'=>'Trash Vehicle!',
            'type'=>'review_by_user',
            'category'=>'negative_review',
        ]);

        Suggestion::create([
            'text'=>'Drunk Customer!',
            'type'=>'review_by_rider',
            'category'=>'negative_review',
        ]);
        
        Suggestion::create([
            'text'=>'Great Customer!',
            'type'=>'review_by_rider',
            'category'=>'positive_review',
        ]);

        Suggestion::create([
            'text'=>'Good Customer!',
            'type'=>'review_by_rider',
            'category'=>'positive_review',
        ]);


        Suggestion::create([
            'text'=>'Rider was too Late!',
            'type'=>'booking_cancel_by_user',
            'category'=>'',
        ]);

        Suggestion::create([
            'text'=>'Customer was unreachable!',
            'type'=>'booking_cancel_by_rider',
            'category'=>'',
        ]);

    }
}
