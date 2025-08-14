<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Sortable
{
    /**
     * Scope a query to apply sorting.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSort(Builder $query, $field, $direction = 'asc')
    {
        if (empty($field) || !isset($this->sortable)) {
            return $query;
        }

        if (in_array($field, $this->sortable)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            return $query->orderBy($field, $direction);
        }

        return $query;
    }

    /**
     * Scope a query to apply sorting from request.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortFromRequest(Builder $query, Request $request)
    {
        $sortField = $request->get('sort_by');
        $sortDirection = $request->get('sort_direction', 'asc');
        
        if ($sortField) {
            return $this->scopeSort($query, $sortField, $sortDirection);
        }
        
        return $query;
    }

    /**
     * Scope a query to apply multiple sorting.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $sorts
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortMultiple(Builder $query, array $sorts)
    {
        if (empty($sorts) || !isset($this->sortable)) {
            return $query;
        }

        foreach ($sorts as $field => $direction) {
            if (in_array($field, $this->sortable)) {
                $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    /**
     * Scope a query to apply sorting with custom logic.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $field
     * @param  string  $direction
     * @param  callable  $callback
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortWith(Builder $query, $field, $direction = 'asc', callable $callback = null)
    {
        if (empty($field) || !isset($this->sortable)) {
            return $query;
        }

        if (in_array($field, $this->sortable)) {
            if ($callback && is_callable($callback)) {
                return $callback($query, $field, $direction);
            }
            
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            return $query->orderBy($field, $direction);
        }

        return $query;
    }

    /**
     * Scope a query to sort by relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $relationship
     * @param  string  $field
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByRelationship(Builder $query, $relationship, $field, $direction = 'asc')
    {
        if (empty($relationship) || empty($field)) {
            return $query;
        }

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        
        return $query->join($relationship, "{$relationship}.id", '=', "{$this->getTable()}.{$relationship}_id")
                    ->orderBy("{$relationship}.{$field}", $direction)
                    ->select("{$this->getTable()}.*");
    }

    /**
     * Scope a query to sort by custom expression.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $expression
     * @param  string  $direction
     * @param  array  $bindings
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByExpression(Builder $query, $expression, $direction = 'asc', array $bindings = [])
    {
        if (empty($expression)) {
            return $query;
        }

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        
        if (!empty($bindings)) {
            return $query->orderByRaw($expression, $bindings)->orderBy($expression, $direction);
        }
        
        return $query->orderByRaw($expression)->orderBy($expression, $direction);
    }

    /**
     * Scope a query to sort by distance (for geographical data).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  float  $lat
     * @param  float  $lng
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByDistance(Builder $query, $lat, $lng, $direction = 'asc')
    {
        if (!isset($this->sortable) || !in_array('distance', $this->sortable)) {
            return $query;
        }

        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
        
        $distanceFormula = "(
            6371 * acos(
                cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude))
            )
        )";
        
        return $query->selectRaw("*, {$distanceFormula} as distance", [$lat, $lng, $lat])
                    ->orderBy('distance', $direction);
    }

    /**
     * Scope a query to sort by relevance (for search results).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $searchTerm
     * @param  array  $fields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByRelevance(Builder $query, $searchTerm, array $fields = [])
    {
        if (empty($searchTerm)) {
            return $query;
        }

        $searchableFields = !empty($fields) ? $fields : ($this->searchable ?? []);
        
        if (empty($searchableFields)) {
            return $query;
        }

        $relevanceFormula = [];
        $bindings = [];
        
        foreach ($searchableFields as $field) {
            $relevanceFormula[] = "CASE 
                WHEN {$field} = ? THEN 100
                WHEN {$field} LIKE ? THEN 50
                WHEN {$field} LIKE ? THEN 25
                ELSE 0
            END";
            
            $bindings[] = $searchTerm;
            $bindings[] = "{$searchTerm}%";
            $bindings[] = "%{$searchTerm}%";
        }
        
        $relevanceExpression = '(' . implode(' + ', $relevanceFormula) . ') as relevance';
        
        return $query->selectRaw("*, {$relevanceExpression}", $bindings)
                    ->orderBy('relevance', 'desc');
    }

    /**
     * Get sortable fields.
     *
     * @return array
     */
    public function getSortableFields()
    {
        return $this->sortable ?? [];
    }

    /**
     * Check if field is sortable.
     *
     * @param  string  $field
     * @return bool
     */
    public function isSortable($field)
    {
        return in_array($field, $this->sortable ?? []);
    }

    /**
     * Get available sort directions.
     *
     * @return array
     */
    public function getSortDirections()
    {
        return [
            'asc' => 'تصاعدي',
            'desc' => 'تنازلي'
        ];
    }

    /**
     * Get default sort field and direction.
     *
     * @return array
     */
    public function getDefaultSort()
    {
        return [
            'field' => $this->sortable[0] ?? 'id',
            'direction' => 'desc'
        ];
    }

    /**
     * Validate sort parameters.
     *
     * @param  string  $field
     * @param  string  $direction
     * @return bool
     */
    public function validateSort($field, $direction)
    {
        if (!in_array($field, $this->sortable ?? [])) {
            return false;
        }
        
        if (!in_array(strtolower($direction), ['asc', 'desc'])) {
            return false;
        }
        
        return true;
    }

    /**
     * Get sort options for forms.
     *
     * @return array
     */
    public function getSortOptions()
    {
        $options = [];
        
        foreach ($this->sortable ?? [] as $field) {
            $options[$field] = $this->getFieldLabel($field);
        }
        
        return $options;
    }

    /**
     * Get human-readable field label.
     *
     * @param  string  $field
     * @return string
     */
    protected function getFieldLabel($field)
    {
        $labels = [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
            'price' => 'السعر',
            'status' => 'الحالة',
            'priority' => 'الأولوية',
            'serial_number' => 'الرقم التسلسلي',
            'tracking_number' => 'رقم التتبع',
            'estimated_delivery_date' => 'تاريخ التسليم المتوقع',
            'actual_delivery_date' => 'تاريخ التسليم الفعلي'
        ];
        
        return $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }
}