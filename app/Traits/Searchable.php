<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    /**
     * Scope a query to search in searchable fields.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $search)
    {
        if (empty($search) || !isset($this->searchable)) {
            return $query;
        }

        $query->where(function ($q) use ($search) {
            foreach ($this->searchable as $field) {
                $q->orWhere($field, 'LIKE', "%{$search}%");
            }
        });

        return $query;
    }

    /**
     * Scope a query to search in specific fields.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  array  $fields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchIn(Builder $query, $search, array $fields)
    {
        if (empty($search) || empty($fields)) {
            return $query;
        }

        $query->where(function ($q) use ($search, $fields) {
            foreach ($fields as $field) {
                $q->orWhere($field, 'LIKE', "%{$search}%");
            }
        });

        return $query;
    }

    /**
     * Scope a query to search with exact match.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  string  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchExact(Builder $query, $search, $field)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where($field, $search);
    }

    /**
     * Scope a query to search with partial match at start.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  string  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchStartsWith(Builder $query, $search, $field)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where($field, 'LIKE', "{$search}%");
    }

    /**
     * Scope a query to search with partial match at end.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @param  string  $field
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchEndsWith(Builder $query, $search, $field)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where($field, 'LIKE', "%{$search}");
    }

    /**
     * Get searchable fields.
     *
     * @return array
     */
    public function getSearchableFields()
    {
        return $this->searchable ?? [];
    }

    /**
     * Check if field is searchable.
     *
     * @param  string  $field
     * @return bool
     */
    public function isSearchable($field)
    {
        return in_array($field, $this->searchable ?? []);
    }
}