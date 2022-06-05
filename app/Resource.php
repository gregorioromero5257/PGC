<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Resource extends Model
{
    // use Searchable;
    use HasApiTokens;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['folder'] = ($this->folder ? $this->folder->name : '');

        return array(
            'name' => $array['name'],
            'type' => $array['type'],
            'folder' => $array['folder'],
            'folder_id' => $array['folder_id'],
            'file' => $array['file'],
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'folder_id', 'file','uri','status'
    ];

    public function folder()
    {
        return $this->belongsTo('App\Folder');
    }
}
