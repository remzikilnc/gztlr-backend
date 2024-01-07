<?php

namespace App\Services\Game;

use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Repositories\GameRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Throwable;

class GameService extends BaseService
{
    protected Game $game;
    protected GameRepository $gameRepository;

    public function __construct(Game $game, GameRepository $gameRepository)
    {
        $this->game = $game;
        $this->gameRepository = $gameRepository;
    }

    public function index()
    {
        return app(GameRepository::class)->getAllGamesPaginated();
    }

    public function show(Game $game): GameResource
    {
        return GameResource::make($game);
    }

    public function store($data): GameResource
    {
        $game = app(StoreGameData::class)->execute(new Game, $data);

        // event(new GameCreated($game));

        return new GameResource($game);
    }

    /**
     * @throws Throwable
     */
    public function update(Game $game, $data): GameResource
    {
        app(StoreGameData::class)->execute($game, $data);
        return new GameResource($game->fresh());
    }

    public function statistics() {
        // SQL sorgusunu daha okunabilir hale getirme
        $sql = "
        SELECT
            COUNT(*) as total_games,
            SUM(CASE WHEN is_published = true THEN 1 ELSE 0 END) as published_games,
            SUM(CASE WHEN is_published = false THEN 1 ELSE 0 END) as unpublished_games,
            SUM(CASE WHEN created_at >= CURRENT_DATE - INTERVAL 7 DAY THEN 1 ELSE 0 END) as new_games_last_7_days,
            SUM(CASE WHEN created_at < CURRENT_DATE - INTERVAL 7 DAY AND created_at >= CURRENT_DATE - INTERVAL 14 DAY THEN 1 ELSE 0 END) as new_games_previous_7_days
        FROM games;
    ";

        $result = DB::select($sql);

        // Sonuçları almak için null coalescing operatörü kullanma
        $newGamesLast7Days = $result[0]->new_games_last_7_days ?? 0;
        $newGamesPrevious7Days = $result[0]->new_games_previous_7_days ?? 0;

        // Oyun büyüme yüzdesini hesaplama
        $gameGrowthPercentageComparedToLastWeek = 0;
        if ($newGamesPrevious7Days > 0) {
            $gameGrowthPercentageComparedToLastWeek = (($newGamesLast7Days - $newGamesPrevious7Days) / $newGamesPrevious7Days) * 100;
        } elseif ($newGamesLast7Days > 0) {
            $gameGrowthPercentageComparedToLastWeek = 100;
        }

        // Sonuçları döndürme
        return [
            'total_games' => $result[0]->total_games ?? 0,
            'published_games' => $result[0]->published_games ?? 0,
            'unpublished_games' => $result[0]->unpublished_games ?? 0,
            'new_games_last_7_days' => $newGamesLast7Days,
            'new_games_previous_7_days' => $newGamesPrevious7Days,
            'game_growth_percentage_compared_to_last_week' => number_format($gameGrowthPercentageComparedToLastWeek, 2),
        ];
    }

    public function destroy(Game $game){
       // event(new GameDeleted($game));
        $game->delete();
    }
}
