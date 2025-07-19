<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// @phpstan-ignore trait.unused
trait HasParentResource
{
    public Model|int|string|null $parent = null;

    public function bootHasParentResource(): void
    {
        if ($parent = (request()->route('parent') ?? request()->input('parent'))) {
            $parentResource = $this->getParentResource();

            $this->parent = $parentResource::resolveRecordRouteBinding($parent);

            if (! $this->parent) {
                throw new ModelNotFoundException;
            }
        }
    }

    public function getParentResource(): string
    {
        if (! isset(static::$parentResource)) {
            throw new Exception('Parent resource is not set for '.static::class);
        }

        return static::$parentResource;
    }

    protected function applyFiltersToTableQuery(Builder $query): Builder
    {
        return $query->where(str($this->parent?->getTable())->singular()->append('_id'), $this->parent->getKey());
    }

    public function getBreadcrumbs(): array
    {
        $resource = $this->getResource();
        $parentResource = $this->getParentResource();

        $breadcrumbs = [
            $parentResource::getUrl() => $parentResource::getBreadCrumb(),
            '#parent' => $parentResource::getRecordTitle($this->parent),
        ];

        // Breadcrumb to child.index or parent.view
        $childIndex = $resource::getPluralModelLabel().'.index';
        $parentView = 'view';

        if ($parentResource::hasPage($childIndex)) {
            $url = $parentResource::getUrl($childIndex, ['parent' => $this->parent]);
            $breadcrumbs[$url] = $resource::getBreadCrumb();
        } elseif ($parentResource::hasPage($parentView)) {
            $url = $parentResource::getUrl($parentView, ['record' => $this->parent]);
            $breadcrumbs[$url] = $resource::getBreadCrumb();
        }

        if (isset($this->record)) {
            $breadcrumbs['#'] = $resource::getRecordTitle($this->record);
        }

        $breadcrumbs[] = $this->getBreadCrumb();

        return $breadcrumbs;
    }
}
