<?php

namespace App\Models\Scopes;

trait Searchable
{

    /**
     * Apply search query to all fillable fields.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $search
     * @param  array|null  $fillable
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeofSearch(
        \Illuminate\Database\Eloquent\Builder $query, 
        string|null $search, array|null $fillable = null
        ): \Illuminate\Database\Eloquent\Builder
    {
        if ($search) {
            $fillable = $fillable ?: $this->getFillable();
            // $relationships = /** get the relationships of teh current model */

            foreach ($fillable as $field) {
                $query->orWhere($field, $search);
            }
        }

        return $query;
    }
}
