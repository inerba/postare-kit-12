<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Postare\Blog\Models\MenuItem;

/**
 * @method morphMany(string $class, string $string)
 */
trait HasMenuItem
{
    /**
     * Get all menu items associated with the model.
     */
    public function menu_items(): MorphMany
    {
        return $this->morphMany(MenuItem::class, 'menuable');
    }

    /**
     * The "booting" method of the trait.
     * Clean cache & Transform the menu item in placeholder when the related item is deleted.
     */
    protected static function bootHasMenuItem(): void
    {
        // Elimina la cache del menu
        static::saved(function ($model) {
            $model->menu_items()->each(function ($menuItem) {
                cache()->forget("filament-blog.menu_item.url.{$menuItem->id}");
                cache()->forget("filament-blog.menu.{$menuItem->menu->code}");
            });
        });

        static::deleted(function ($model) {
            $model->menu_items()->each(function ($menuItem) {
                cache()->forget("filament-blog.menu_item.url.{$menuItem->id}");
                cache()->forget("filament-blog.menu.{$menuItem->menu->code}");
            });

            // Trasforma il menu item in placeholder
            $model->menu_items()->update([
                'type' => 'placeholder',
                'menuable_id' => null,
                'menuable_type' => null,
            ]);

            // Elimina il menu item
            // $model->menu_items()->delete();
        });
    }
}
