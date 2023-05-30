<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Media::create([
            'mimetype' => 'image/jpeg',
            'name' => 'caroline-lm--m-4tYmtLlI-unsplash.jpg',
            'path' => public_path('samples/caroline-lm--m-4tYmtLlI-unsplash.jpg'),
            'ext' => 'jpg',
            'size' => 999999,
            'original_id' => null,
            'width' => 333,
            'height' => 222
        ]);
        Media::create([
            'mimetype' => 'image/jpeg',
            'name' => 'owen-beard-DK8jXx1B-1c-unsplash.jpg',
            'path' => public_path('samples/owen-beard-DK8jXx1B-1c-unsplash.jpg'),
            'ext' => 'jpg',
            'size' => 300000,
            'original_id' => null,
            'width' => 300,
            'height' => 200
        ]);
    }
}
