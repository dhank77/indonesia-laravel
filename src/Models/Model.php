<?php

namespace Hitech\IndonesiaLaravel\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public $timestamps = false;

    protected $keyType = 'string';

    protected array $searchableColumns = ['code', 'name'];

    protected $casts = [
        'meta' => 'array',
    ];

    protected $guarded = [];

    /**
     * Apply a dynamic search filter to the query, including support for nested relations.
     *
     * Example usage:
     * Province::search('Jawa')->get();
     *
     * This scope will search the configured searchable columns on the model,
     * and can also apply conditions to related models using dot notation
     * (e.g., 'cities.name', 'districts.code').
     *
     * @param Builder $query The query builder instance.
     * @param string|null $keyword The keyword to search for.
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (!$keyword || empty($this->searchableColumns)) {
            return $query;
        }

        $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

        return $query->where(function ($q) use ($keyword, $operator) {
            foreach ($this->searchableColumns as $field) {
                $segments = explode('.', $field);
                $this->buildNestedConditions($q, $segments, $keyword, $operator);
            }
        });
    }

    /**
     * Build nested where/orWhereHas conditions for the query.
     *
     * This method helps apply search filters on related models
     * by walking through the dot-notated relationship path.
     *
     * @param Builder $query The query builder.
     * @param array $segments The parts of the dot-notated field.
     * @param string $search The search keyword.
     * @param string $operator The SQL operator to use (LIKE or ILIKE).
     */
    protected function buildNestedConditions(Builder $query, array $segments, string $search, string $operator): void
    {
        $field = array_pop($segments);

        if (empty($segments)) {
            $query->orWhere($field, $operator, "%{$search}%");
        } else {
            $relation = implode('.', $segments);

            $query->orWhereHas($relation, function ($q) use ($field, $operator, $search) {
                $q->where($field, $operator, "%{$search}%");
            });
        }
    }

}
