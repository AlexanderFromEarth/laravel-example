<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class AnimalController extends Controller
{
    protected $animalModel;

    public function __construct()
    {
        $this->animalModel = new Animal();        
    }

    public function list(Request $request, $page = 1)
    {
        try {
            $options = $request->toArray();
            $perPage = 20;
            $validated = Validator::make($options, [
                'filterType' => ['nullable', Rule::in(["cat", "dog", "bird"])],
                'filterSex' => ['nullable', Rule::in(["female", "male"])],
                'filterFDate' => ['nullable', 'date'],
                'filterLDate' => ['nullable', 'date'],
                'filterCountry' => ['nullable'],
                'sortField' => ['nullable', Rule::in(["birth_day", "weight"])],
                'sortDir' => ['required_with:sortField', Rule::in(["asc", "desc"])],
            ])->validated();
            $result = $this->animalModel->getList($page, $perPage, $validated);
            return response()->json([
                "data" => $result["data"],
                "pages" => $result["totalPages"],
                "success" => true,
                "message" => "Fetched",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e,
                'opts' => $options,
            ]);
        }
    }
    public function form(Request $request, $id = null) {
        try {
            $json = $request->json()->all();
            unset($json["animal_id"]);
            $data = $this->animalModel->newInstance()->fill($json);
            $res = $id
                ? $this->animalModel->updateRecord($id, $data)
                : $this->animalModel->createRecord($data);
            if ($res) {
                return response()->json([
                    'success' => $res,
                    'message' => $id ? 'Edited' : 'Created',
                ]);
            } else {
                return response()->json([
                    'success' => $res,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e,
            ]);
        }
    }

    public function delete($id) {
        try {
            $this->animalModel->deleteRecord($id);
            return response()->json([
                'success' => true,
                'message' => 'Deleted',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e,
            ]);
        }
    }
    public function generate(Request $request) {
        try {
            $json = $request->json()->all();
            $cnt = $json["count"];
            $isAdd = $json["add"];
            $data = $this->animalModel->generate($cnt);

            if ($isAdd === true) {
                $cnt = 0;
                if (is_array($data)) {
                    foreach ($data as $rec) {
                        if ($this->animalModel->createRecord($rec)) {
                            $cnt += 1;
                        }
                    }
                } else {
                    if ($this->animalModel->createRecord($data)) {
                        $cnt += 1;
                    }
                }
            } else {
                $cnt = 0;
                if (is_array($data)) {
                    foreach ($data as $rec) {
                        $newData[] = $rec;
                    }
                } else {
                    $newData = $data;
                }
            }
            return response()->json([
                'data' => $isAdd === true ? $cnt : $data,
                'success' => true,
                'message' => 'Generated',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e,
            ]);
        }
    }
}
