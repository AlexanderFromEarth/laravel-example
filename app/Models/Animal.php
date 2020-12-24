<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class Animal extends Model
{
    use HasFactory;

    private $sexes = ['female', 'male'];

    protected $table = "animals";
    protected $primaryKey = 'animal_id';
    protected $fillable = ["type", "sex", "birth_day", "name", "owner", "weight", "country"];

    public $timestamps = false;

    public function getSexAttribute($sex) {
        return $this->sexes[(int) $sex];
    }

    public function setSexAttribute($sex) {
        $this->attributes['sex'] = \array_search($sex, $this->sexes);
    }

    public static function createRecord($data = null) {
        if (!$data) {
            $data = self::generate();
        }
        try {
            $data = $data->attributes;
            Validator::make($data, [
                'name' => [
                    'required',
                    'unique:animals,name',
                    'regex:/^[a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+$/u'
                ],
                'type' => ['required', Rule::in(["cat", "dog", "bird"])],
                'sex' => ['required', Rule::in([0, 1])],
                'birth_day' => ['required', 'date'],
                'country' => ['required', 'regex:/^([a-zA-Zа-яА-Яеё]+ ?)+$/u'],
                'owner' => ['required', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+$/u'],
                'weight' => ['required', 'gt:0'],
            ])->validate(); 
            return self::query()->insert($data);
        } catch(QueryException $e) {
            return false;
        }
    }

    public static function getList($page, $perPage, $options = null) {
        $options = Validator::make($options, [
                'filterType' => ['nullable', Rule::in(["cat", "dog", "bird"])],
                'filterSex' => ['nullable', Rule::in(["female", "male"])],
                'filterFDate' => ['nullable', 'date'],
                'filterLDate' => ['nullable', 'date'],
                'filterCountry' => ['nullable'],
                'sortField' => ['nullable', Rule::in(["birth_day", "weight"])],
                'sortDir' => ['required_with:sortField', Rule::in(["asc", "desc"])],
            ])->validated();
        $builder = self::query();
        if (isset($options['filterType']) && $options['filterType'] !== null) {
            $builder->where('type', '=', $options['filterType']);
        }
        if (isset($options['filterCountry']) && $options['filterCountry'] !== null) {
            $builder->where('country', 'like', $options['filterCountry']."%");
        }
        if (isset($options['filterSex']) && $options['filterSex'] !== null) {
            $builder->where('sex', '=', $options['filterSex'] === 'female' ? 0 : 1);
        }
        if (isset($options['filterFDate']) && $options['filterFDate'] !== null) {
            $builder->where('birth_day', '>=', $options['filterFDate']);
        }
        if (isset($options['filterLDate']) && $options['filterLDate'] !== null) {
            $builder->where('birth_day', '<=', $options['filterLDate']);
        }
        if (isset($options['sortDir']) && isset($options['sortField']) && $options['sortField'] !== null && $options['sortDir'] !== null) {
            $builder->orderBy($options['sortField'], $options['sortDir']);
        }
        return [
            "pages"    => ceil($builder->count() / $perPage),
            "data"          => $builder->offset(($page - 1) * $perPage)->limit($perPage)->select()->get()->toArray()
        ];
    }

    public static function updateRecord($id, $data) {
        try {
            $data = $data->attributes;
            unset($data["animal_id"]);
            Validator::make($data, [
                'name' => [
                    'required',
                    Rule::unique("animals", "name")->ignore($id, "animal_id"),
                    'regex:/^[a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+$/u'
                ],
                'type' => ['required', Rule::in(["cat", "dog", "bird"])],
                'sex' => ['required', Rule::in([0, 1])],
                'birth_day' => ['required', 'date'],
                'country' => ['required', 'regex:/^([a-zA-Zа-яА-Яеё]+ ?)+$/u'],
                'owner' => ['required', 'regex:/^[a-zA-Zа-яА-ЯёЁ]+ [a-zA-Zа-яА-ЯёЁ]+$/u'],
                'weight' => ['required', 'gt:0'],
            ])->validate(); 
            return self::query()->where("animal_id", "=", $id)->update($data);
        } catch(QueryException $e) {
            return false;
        }
    }

    public static function deleteRecord($id) {
        return self::query()->where("animal_id", "=", $id)->delete();
    }

    public static function generate($count = null) {
        $count = (int) $count;
        if ($count > 1) {
            return array_map(function ($x) {
                return self::factory()->makeOne();
            }, range(1, $count));
        } else {
            return self::factory()->makeOne();
        }
    }
}
