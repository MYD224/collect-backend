<?php

namespace App\Modules\Navigation\Infrastructure\Persistence\Eloquent\Models;

use App\Modules\Navigation\Infrastructure\Persistence\Eloquent\Models\StructureMenuOverride as EntitiesStructureMenuOverride;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use SoftDeletes, HasUuids;
    public $incrementing = false;

    protected $fillable = [
        'id',
        'module_id',
        'parent_id',
        'code',
        'type', //menu, tab
        'route_path',
        'icon',
        'default_label', // {"en": "Invoices", "fr": "Factures"}
        'sort_order',
        'created_by_id',
        'updated_by_id'
    ];

    protected $casts = ['default_label' => 'array', 'id' => 'string'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID if not already set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function overrides()
    {
        return $this->hasMany(EntitiesStructureMenuOverride::class);
    }

    // Business Logic: Determine if an action is permitted for a user
    public function isActionAllowed($user, string $action): bool
    {
        return $user->can("{$this->code}.{$action}");
    }

    // Business Logic: Resolve translated label
    public function getLabelForStructure(string $structureId, string $locale): string
    {
        $override = $this->overrides->where('structure_id', $structureId)->first();
        return $override?->custom_label[$locale] ?? ($this->default_label[$locale] ?? $this->default_label['fr']);
    }
}
