<?php

namespace App\Services\Game;

use App\Models\Category;
use App\Models\Game;
use App\Models\Image;
use App\Models\Keyword;
use App\Models\Language;
use App\Traits\StoresMediaImages;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class StoreGameData
{
    use StoresMediaImages;
    private Game $game;
    private array $data;
    private Image $image;
    private Keyword $keyword;
    private Language $language;
    private Category $category;
    private array $config;

    public function __construct(
        Image    $image,
        Keyword  $keyword,
        Language $language,
        Category $category
    )
    {
        $this->image = $image;
        $this->keyword = $keyword;
        $this->language = $language;
        $this->category = $category;
    }


    public function execute(Game $game, array $data, array $config = []): Game
    {
        $this->game = $game;
        $this->data = $data;
        $this->config = $config;

        $this->persistData();
        $this->persistRelations();

        return $this->game;
    }

    private function persistData(): void
    {
        $gameData = array_filter($this->data, function ($value) {
            return !is_array($value);
        });

        $this->game->fill($gameData)->save();
    }

    private function persistRelations(): void
    {
        $relations = array_filter($this->data, function ($value) {
            return is_array($value);
        });

        foreach ($relations as $name => $values) {
            switch ($name) {
                case 'images':
                    $this->storeImages($values, $this->game);
                    break;
                case 'prices':
                    $this->storePrices($values, $this->game);
                    break;
                case 'categories':
                    foreach ($values as $value) {
                        if (isset($value['name'])) {
                            $this->updateRelationByType($values, 'category');
                        } elseif (isset($value['steam_id'])) {
                            $this->updateRelationBySteamId($value['steam_id'], 'category');
                        }
                    }
                    break;
                case 'languages':
                    $this->updateRelationByType($values, 'language');
                    break;
                case 'keywords':
                    foreach ($values as $value) {
                        if (isset($value['name'])) {
                            $this->updateRelationByType($values, 'keyword');
                        } elseif (isset($value['steam_id'])) {
                            $this->updateRelationBySteamId($value['steam_id'], 'keyword');
                        }
                    }
                    break;
            }
        }
    }

    private function updateRelationByType($data, $type): void
    {
        $values = collect($data)->map(function ($item) use ($data) {
            return [
                'slug' => slugify($item['slug'] ?? slugify($item['name'])),
                'name' => ucfirst($item['name']),
            ];
        });


        $data = $this->insertOrRetrieveByType($values, $type);

        $relation = $this->getRelation($type);

        $relation->sync($data->pluck('id'));
    }

    private function updateRelationBySteamId($data, $type): void
    {
        $data = $this->retrieveBySteamId($data, $type);

        $relation = $this->getRelation($type);
        $relation->syncWithoutDetaching($data->pluck('id'));
    }

    private function insertOrRetrieveByType($values, $type): array|Collection
    {
        return match ($type) {
            'category' => $this->category->insertOrRetrieveBySlug($values),
            'language' => $this->language->insertOrRetrieveBySlug($values),
            'keyword' => $this->keyword->insertOrRetrieveBySlug($values),
        };
    }

    private function retrieveBySteamId($values, $type): array|Collection
    {
        return match ($type) {
            'category' => $this->category->getBySteamId($values),
            'language' => $this->language->getBySteamId($values),
            'keyword' => $this->keyword->getBySteamId($values),
        };
    }

    private function getRelation($type): BelongsToMany
    {
        return match ($type) {
            'category' => $this->game->categories(),
            'language' => $this->game->languages(),
            'keyword' => $this->game->keywords(),
        };
    }

    private function storePrices($values, Game $game): void
    {
        if (is_array($values) && isset($values[0]) && is_array($values[0])) {
            foreach ($values as $value) {
                $value['game_id'] = $game->id;
                $game->prices()->insert($value);
            }
        } elseif (is_array($values)) {
            $values['game_id'] = $game->id;
            $game->prices()->insert([$values]);
        }
    }
}
