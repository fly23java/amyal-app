<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    /**
     * Scope a query to apply filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, array $filters)
    {
        if (empty($filters) || !isset($this->filterable)) {
            return $query;
        }

        foreach ($filters as $field => $value) {
            if (in_array($field, $this->filterable) && !empty($value)) {
                $this->applyFilter($query, $field, $value);
            }
        }

        return $query;
    }

    /**
     * Scope a query to apply filters from request.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterFromRequest(Builder $query, Request $request)
    {
        $filters = $request->only($this->filterable ?? []);
        
        return $this->scopeFilter($query, $filters);
    }

    /**
     * Apply specific filter to query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  mixed  $value
     * @return void
     */
    protected function applyFilter(Builder $query, $field, $value)
    {
        // Check if custom filter method exists
        $method = 'filter' . ucfirst($field);
        if (method_exists($this, $method)) {
            $this->$method($query, $value);
            return;
        }

        // Apply default filter logic
        if (is_array($value)) {
            if (isset($value['operator']) && isset($value['value'])) {
                $this->applyOperatorFilter($query, $field, $value['operator'], $value['value']);
            } else {
                $query->whereIn($field, $value);
            }
        } elseif (is_string($value) && str_contains($value, ',')) {
            $values = array_map('trim', explode(',', $value));
            $query->whereIn($field, $values);
        } elseif (is_string($value) && str_contains($value, '|')) {
            $values = array_map('trim', explode('|', $value));
            $query->whereIn($field, $values);
        } elseif (is_string($value) && str_contains($value, ':')) {
            $this->applyRangeFilter($query, $field, $value);
        } else {
            $query->where($field, $value);
        }
    }

    /**
     * Apply operator-based filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $operator
     * @param  mixed  $value
     * @return void
     */
    protected function applyOperatorFilter(Builder $query, $field, $operator, $value)
    {
        switch ($operator) {
            case '=':
                $query->where($field, $value);
                break;
            case '!=':
                $query->where($field, '!=', $value);
                break;
            case '>':
                $query->where($field, '>', $value);
                break;
            case '>=':
                $query->where($field, '>=', $value);
                break;
            case '<':
                $query->where($field, '<', $value);
                break;
            case '<=':
                $query->where($field, '<=', $value);
                break;
            case 'like':
                $query->where($field, 'LIKE', "%{$value}%");
                break;
            case 'not_like':
                $query->where($field, 'NOT LIKE', "%{$value}%");
                break;
            case 'starts_with':
                $query->where($field, 'LIKE', "{$value}%");
                break;
            case 'ends_with':
                $query->where($field, 'LIKE', "%{$value}");
                break;
            case 'null':
                $query->whereNull($field);
                break;
            case 'not_null':
                $query->whereNotNull($field);
                break;
            case 'in':
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereIn($field, $values);
                break;
            case 'not_in':
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereNotIn($field, $values);
                break;
            case 'between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($field, $value);
                }
                break;
            case 'not_between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereNotBetween($field, $value);
                }
                break;
            default:
                $query->where($field, $value);
        }
    }

    /**
     * Apply range filter (e.g., "2023-01-01:2023-12-31").
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $value
     * @return void
     */
    protected function applyRangeFilter(Builder $query, $field, $value)
    {
        $range = explode(':', $value);
        if (count($range) === 2) {
            $start = trim($range[0]);
            $end = trim($range[1]);
            
            if (!empty($start) && !empty($end)) {
                $query->whereBetween($field, [$start, $end]);
            } elseif (!empty($start)) {
                $query->where($field, '>=', $start);
            } elseif (!empty($end)) {
                $query->where($field, '<=', $end);
            }
        }
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $startDate
     * @param  string  $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByDateRange(Builder $query, $field, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween($field, [$startDate, $endDate]);
        } elseif ($startDate) {
            return $query->where($field, '>=', $startDate);
        } elseif ($endDate) {
            return $query->where($field, '<=', $endDate);
        }
        
        return $query;
    }

    /**
     * Scope a query to filter by multiple values.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByMultiple(Builder $query, $field, array $values)
    {
        if (!empty($values)) {
            return $query->whereIn($field, $values);
        }
        
        return $query;
    }

    /**
     * Scope a query to filter by boolean value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  bool  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByBoolean(Builder $query, $field, $value)
    {
        if (is_bool($value)) {
            return $query->where($field, $value);
        }
        
        return $query;
    }

    /**
     * Get filterable fields.
     *
     * @return array
     */
    public function getFilterableFields()
    {
        return $this->filterable ?? [];
    }

    /**
     * Check if field is filterable.
     *
     * @param  string  $field
     * @return bool
     */
    public function isFilterable($field)
    {
        return in_array($field, $this->filterable ?? []);
    }

    /**
     * Get available filter operators.
     *
     * @return array
     */
    public function getFilterOperators()
    {
        return [
            '=' => 'يساوي',
            '!=' => 'لا يساوي',
            '>' => 'أكبر من',
            '>=' => 'أكبر من أو يساوي',
            '<' => 'أصغر من',
            '<=' => 'أصغر من أو يساوي',
            'like' => 'يحتوي على',
            'not_like' => 'لا يحتوي على',
            'starts_with' => 'يبدأ بـ',
            'ends_with' => 'ينتهي بـ',
            'null' => 'فارغ',
            'not_null' => 'غير فارغ',
            'in' => 'ضمن القيم',
            'not_in' => 'ليس ضمن القيم',
            'between' => 'بين',
            'not_between' => 'ليس بين'
        ];
    }
}