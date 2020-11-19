<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class AnimalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Animal::class;
    protected $genPath = "gen.json";
    protected $genData;

    public function __construct() {
        parent::__construct();
        $this->genData = json_decode(Storage::get($this->genPath), $assoc = true);
    }
        
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (config('app.env') === 'dev') {
            $sexes = ['female', 'male'];
            $types = ['cat', 'dog', 'bird'];
            $sex = array_rand($sexes);
            $titles = $this->genData['titles_' . $sexes[$sex]];
            $adjectives = $this->genData['adjectives'];
            $nouns = $this->genData['nouns_' . $sexes[$sex]];
            $firstNames = $this->genData['firstNames'];
            $lastNames = $this->genData['lastNames'];
            $countries = $this->genData['countries'];
            $animal['name'] =
                $titles[array_rand($titles)] .
                ' ' .
                $adjectives[array_rand($adjectives)] .
                $this->genData['adj_' . $sexes[$sex]] .
                ' ' .
                $nouns[array_rand($nouns)];
            $animal['owner'] =
                $firstNames[array_rand($firstNames)] .
                ' ' .
                $lastNames[array_rand($lastNames)];
            $animal['country'] = $countries[array_rand($countries)];
            $animal['type'] = $types[array_rand($types)];
            $animal['weight'] = rand(0.6 * 1000000, 5 * 1000000) / 1000000;
            $animal['birth_day'] = date('Y-m-d', time() - rand());
            $animal['sex'] = $sexes[$sex];
            return $animal;
        }
    }
}
