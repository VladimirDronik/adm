<?php

namespace App\Console\Commands;

use App\Models\Image;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetImagesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить актуальные данные изображений';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client(['base_uri' => config('images_api.base_uri')]);

        $logResponse = $client->request('GET', '/api/v1/private/log', [
            'query' => [
                'date' => Carbon::now()->subDay()->toIso8601String(),
                'product' => 'adm',
            ],
        ]);

        $logs = json_decode($logResponse->getBody(), true);

        if (array_key_exists('logs', $logs)) {
            foreach ($logs['logs'] as $log) {
                switch ($log['oper']) {
                    case 'add':
                        try {
                            $imageResponse = $client
                                ->request('GET', '/api/v1/private/image?id='.$log['image_id']);

                            if ($imageResponse->getStatusCode() == 200) {
                                $imageData = json_decode($imageResponse->getBody(), true);

                                if (! Image::where('id', $imageData['id'])->exists()) {
                                    Image::create([
                                        'id' => $imageData['id'],
                                        'name' => $imageData['name'],
                                        'style' => $imageData['style'],
                                        'image' => $this->decodeAndSaveImage($imageData['image']),
                                    ]);
                                }
                            }
                        } catch (GuzzleException $e) {
                        }

                        break;
                    case 'edit':
                        $image = Image::find($log['image_id']);

                        if ($image) {
                            Storage::disk('custom')->delete($image->image);
                        } else {
                            $image = new Image();
                        }

                        try {
                            $imageResponse = $client
                                ->request('GET', '/api/v1/private/image?id='.$log['image_id']);

                            if ($imageResponse->getStatusCode() == 200) {
                                $imageData = json_decode($imageResponse->getBody(), true);

                                $image->id = $imageData['id'];
                                $image->name = $imageData['name'];
                                $image->style = $imageData['style'];
                                $image->image = $this->decodeAndSaveImage($imageData['image']);

                                $image->save();
                            }
                        } catch (GuzzleException $e) {
                        }

                        break;
                    case 'del':
                        $image = Image::find($log['image_id']);
                        if ($image) {
                            Storage::disk('custom')->delete($image->image);
                            $image->delete();
                        }

                        break;
                }
            }

            Log::info('Изображения успешно обновлены');
        }
    }

    /**
     * Декодирует изображение из base64 и сохраняет в 'ela/images/views_items'
     *
     * @return string
     */
    private function decodeAndSaveImage(string $image)
    {
        $tempFile = new File(tempnam(sys_get_temp_dir(), 'temp'));
        file_put_contents($tempFile, base64_decode($image));

        $path = Storage::disk('custom')->putFile('ela/images/views_items', $tempFile);
        unlink($tempFile);

        return $path;
    }
}
