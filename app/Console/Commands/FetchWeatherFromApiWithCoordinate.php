<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class FetchWeatherFromApiWithCoordinate extends Command
{
    public const API_URL = 'https://api.openweathermap.org/data/2.5/onecall?lat=%s&lon=%s&appid=%s&units=metric';
    public const API_KEY = 'e90242bb20a2dbd9d0be3d7abca52376';

    /**
     *
     * php artisan app:fetch-weather-from-api-with-coordinate "Adana"
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-weather-from-api-with-coordinate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private City $city;

    /**
     * Execute the console command.
     */
    public function handle(City $city)
    {
        $city = $city->where('name', 'Adana')->first();
        $this->city = $city;
        $data = $this->getWeatherDailyWeathers($city);
        $city->weather()->delete();
        $city->weather()->createMany($data);
        $this->info('Weather data updated for ' . $city->name);

    }

    private function getWeather(City $city): array
    {
        try {
            $response = Http::get($this->getApiUrl($city));
            return json_decode($response->body(), true);
        } catch (\Exception $exception) {
            return [];
        }
    }

    private function getApiUrl(City $city): string
    {
        return sprintf(self::API_URL, $city->lat, $city->lon, $this->getApiKey());
    }

    private function getApiKey(): string
    {
        return self::API_KEY;
    }

    /*foreach ($response['daily'] as $key => $value) {
    $response['daily'][$key] = $this->organizeDailyData($value);
    }*/

    private function organizeData(array $response): array
    {
        return [
            'city_id' => $this->city->id,
            'sunrise' => $this->getDateWithTime($response['sunrise']),
            'sunset' => $this->getDateWithTime($response['sunset']),
            'min_temp' => $response['temp']['min'],
            'max_temp' => $response['temp']['max'],
            'wind_speed' => $response['wind_speed'],
            'wind_deg' => $response['wind_deg'],
            'humidity' => $response['humidity'],
            'applicable_date' => $this->getDate($response['dt']),
            'weather_status' => $response['weather'][0]['main'],
            'weather_description' => $response['weather'][0]['description'],
            'weather_icon' => $response['weather'][0]['icon'],
        ];
    }

    private function getWeatherDailyWeathers(City $city): array
    {
        $response = $this->getWeather($city);
        $daily = $response['daily'];
        return array_map(function ($item) {
            return $this->organizeData($item);
        }, $daily);
    }

    private function getDate(int $date): string
    {
        return Carbon::createFromTimestamp($date)->format('Y-m-d');
    }

    private function getDateWithTime(int $date): string
    {
        return match ($this->city->timezone) {
            'GMT+3' => $this->getFullDateWithTimeDifference($date, 3),
            'GMT+2' => $this->getFullDateWithTimeDifference($date, 2),
            'GMT+1' => $this->getFullDateWithTimeDifference($date, 1),
            default => $this->getFullDateWithTimeDifference($date, 0)
        };
    }

    private function getFullDateWithTimeDifference(int $date, int $timeDifference): string
    {
        if ($timeDifference > 0) {
            return Carbon::createFromTimestamp($date)->addHours($timeDifference)->format('Y-m-d H:i:s');
        } else if ($timeDifference < 0) {
            return Carbon::createFromTimestamp($date)->subHours($timeDifference)->format('Y-m-d H:i:s');
        }
        return Carbon::createFromTimestamp($date)->format('Y-m-d H:i:s');
    }
}
