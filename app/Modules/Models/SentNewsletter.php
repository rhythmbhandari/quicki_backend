<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Models\Newsletter;

class SentNewsletter extends Model
{
    use HasFactory;

    protected $casts = [
        'newsletter_id' => 'integer',
        'subscriber_ids' => 'array'
    ];

    protected $fillable = ([
        'newsletter_id', 'subscriber_ids', 'sent_at',  'created_at', 'updated_at'
    ]);

    protected $appends = [
        
    ]; 


    public function newsletter()
    {
        return $this->belongsTo(Newsletter::class);
    }

}
