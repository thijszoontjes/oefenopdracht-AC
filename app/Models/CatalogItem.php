<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CatalogItem extends Model
{
    protected $fillable = [
        'external_id',
        'sku',
        'title',
        'description',
        'category',
        'brand',
        'price',
        'sale_price',
        'currency',
        'stock',
        'availability',
        'thumbnail_url',
        'source_url',
        'rating',
        'review_count',
        'raw_payload',
        'issues',
        'issue_count',
        'readiness_score',
        'updated_at_source',
    ];

    protected $casts = [
        'price'             => 'float',
        'sale_price'        => 'float',
        'rating'            => 'float',
        'stock'             => 'integer',
        'review_count'      => 'integer',
        'issue_count'       => 'integer',
        'readiness_score'   => 'integer',
        'raw_payload'       => 'array',
        'issues'            => 'array',
        'updated_at_source' => 'datetime',
    ];

    // Heeft dit product directe aandacht nodig?
    public function needsAttention(): bool
    {
        return $this->issue_count > 0;
    }

    // Bevat het een ernstig (high) probleem?
    public function heeftErnstigProbleem(): bool
    {
        if (empty($this->issues)) {
            return false;
        }

        return collect($this->issues)->contains('severity', 'high');
    }

    // Scope: alleen producten met problemen
    public function scopeMetProblemen(Builder $query): Builder
    {
        return $query->where('issue_count', '>', 0);
    }

    // Scope: alleen producten zonder problemen — klaar voor gebruik
    public function scopeKlaarVoorGebruik(Builder $query): Builder
    {
        return $query->where('issue_count', 0);
    }
}
