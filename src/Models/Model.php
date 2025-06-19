<?php

namespace Hitech\IndonesiaLaravel\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\App;

class Model extends EloquentModel
{
    public $timestamps = false;

    protected $keyType = 'string';

    protected $searchableColumns = ['code', 'name'];

    protected $casts = [
        'meta' => 'array',
    ];

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('indonesia.table_prefix') . $this->table;
    }

    public function scopeSearch($query, $keyword)
    {
        if ($keyword && $this->searchableColumns) {
            $query->whereLike($this->searchableColumns, $keyword);
        }
    }
}
