<?php

namespace App\Modules\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;


class Setting extends Model
{
    use HasFactory;//, Config;
    protected $path = 'uploads/setting';

     /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = ['key', 'value', ]; //data_type, group, required, description

    protected $appends = ['path', 'image_path', 'thumbnail_path'];

    public function getPathAttribute()
    {
        return $this->path . '/';
    }


    function getImagePathAttribute()
    {
        if(is_file( $this->path . '/' . $this->value))  {
            return $this->path . '/' . $this->value;
        }else{
            return 'assets/media/noimage.png';
        }
    }
    function getThumbnailPathAttribute()
    {
        if(is_file( $this->path . '/thumb/' . $this->value))  {
            return $this->path . '/thumb/' . $this->value;
        }else{
            return 'assets/media/noimage.png';
        }
    }



    /**
     * Querying the record by $key and returning the value for a given key.
     * @param $key
     */
    public static function get($key)
    {
        $setting = new self();
        $entry = $setting->where('key', $key)->first();
        if (!$entry) {
            return;
        }
        return $entry->value;
    }

    /**
     * First checking if the given $key has any value in the database, then we are updating the value 
     * for the given setting. Next, we are setting the current key/value for setting to the Laravel Configuration, 
     * so we can load them using the Laravel config() helper function.
     * @param $key
     * @param null $value
     * @return bool
     */
    public static function set($key, $value = null)
    {
        $setting = new self();
        $entry = $setting->where('key', $key)->firstOrFail();
        $entry->value = $value;
        $entry->saveOrFail();
        Config::set('key', $value);
        if (Config::get($key) == $value) {
            return true;
        }
        return false;
    }

}
