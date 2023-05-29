<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Model extends EloquentModel
{
    use HasFactory;

    public function filterByRequestQuery(Request $request)
    {
        $like_fields = [
            'first_name',
            'last_name',
            'company_name',
            'title',
            'name',
            'code',
            'address',
            'city',
            'email',
            'phone',
            'mobile',
            'fax'
        ];
        $items = $this;
        $params = $request->query();

        // search over specific model field
        $searchable = isset($this->searchable) ? $this->searchable : $this->fillable;
        foreach ($params as $field => $value) {
            if (in_array($field, $searchable)) {
                if (in_array($field, $like_fields)) {
                    $words = explode(' ', $value);
                    if (mb_strlen($value) <= 1) {
                        $items = $items->where($field, 'LIKE', $value . '%');
                    } else if (count($words) > 1) {
                        $items = $items->orWhere(function ($query) use ($field, $words) {
                            foreach ($words as $word) {
                                $query->where($field, 'LIKE', '%' . $word . '%');
                            }
                        });
                    } else {
                        $items = $items->where($field, 'LIKE', '%' . $value . '%');
                    }
                } else {
                    $items = $items->where($field, '=', $value);
                }
            }
        }


        // 'q' will try and search everything
        if (isset($params['q'])) {
            $phrase = $params['q'];
            $searchable = isset($this->searchable) ? $this->searchable : $this->fillable;
            $items = $items->where(function ($query) use ($phrase, $searchable) {
                $words = explode(' ', $phrase);
                foreach ($searchable as $field) {
                    if (mb_strlen($phrase) <= 1) {
                        $query = $query->orWhere($field, 'LIKE', $phrase . '%');
                    } else if (count($words) > 1) {
                        $query = $query->orWhere(function ($query) use ($field, $words) {
                            foreach ($words as $word) {
                                $query->where($field, 'LIKE', '%' . $word . '%');
                            }
                        });
                    } else {
                        $query = $query->orWhere($field, 'LIKE', '%' . $phrase . '%');
                    }
                }
            });
        }

        return $items;
    }
}
