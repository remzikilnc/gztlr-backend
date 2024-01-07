<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait HandlesSlugOperations
{
    public function insertOrRetrieveBySlug(array|Collection $data): Collection|array
    {
        $normalizedData = $this->normalizeDataForSlugs($data);

        $existingData = $this->getBySlugs($normalizedData->pluck('slug'));

        $new = $this->determineNewEntries($normalizedData, $existingData);
        if ($new->isNotEmpty()) {
            $new->transform(function ($item) {
                $item['created_at'] = Carbon::now();
                $item['updated_at'] = Carbon::now();
                return $item;
            });
            $this->insertEntries($new->toArray());
            return $this->getBySlugs($data->pluck('slug'));
        }

        return $existingData;
    }

    private function normalizeDataForSlugs($data): Collection
    {
        if (!$data instanceof Collection) {
            $data = collect($data);
        }

        return $data->filter()->map(function (array|string $item) {
            if (is_string($item)) {
                return ['slug' => slugify($item), 'name' => $item];
            }
            $item['slug'] = slugify($item['slug']);
            return $item;
        });
    }

    private function determineNewEntries(Collection $data, Collection $existing): Collection
    {
        return $data->filter(function ($item) use ($existing) {
            return !$existing->first(function ($existingData) use ($item) {
                return $existingData['slug'] === slugify($item['slug']);
            });
        });
    }

    abstract protected function getBySlugs(Collection $slugs): Collection;

    abstract protected function insertEntries(array $entries);
}


