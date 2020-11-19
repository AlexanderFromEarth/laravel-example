<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public function createRecord($data = null) {
        if (!$data) {
            $data = $this->generate();
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
            return $this->query()->insert($data);
        } catch(ValidationException $e) {
            return false;
        } catch(QueryException $e) {
            return false;
        }
    }

    public function getList($page, $perPage, $options = null) {
        $builder = $this->query();
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
            "totalPages"    => ceil($builder->count() / $perPage),
            "data"          => $builder->offset(($page - 1) * $perPage)->limit($perPage)->select()->get()->toArray()
        ];
    }

    public function updateRecord($id, $data) {
        try {
            $data = $data->attributes;
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
            return $this->query()->where("animal_id", "=", $id)->update($data);
        } catch(ValidationException $e) {
            return false;
        } catch(QueryException $e) {
            return false;
        }
    }

    public function deleteRecord($id) {
        return $this->query()->where("animal_id", "=", $id)->delete();
    }

    public function generate($count = null) {
        $count = (int) $count;
        if ($count > 1) {
            return $this->factory()->count($count)->make();
        } else {
            return $this->factory()->make();
        }
    }
}
