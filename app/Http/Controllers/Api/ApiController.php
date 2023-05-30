<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use App\Http\Requests\RestRequest;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $allow = [];
    public $deny = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->allowed(__METHOD__);
        $model = new $this->model;

        // filter
        $model = $model->filterByRequestQuery($request);

        // count
        $total = intval($model->count());

        // order
        $sort_by = $request->get('sort_by', 'id');
        $sort_dir = $request->get('sort_dir', 'asc');
        $model = $model->orderBy($sort_by, $sort_dir);

        // paginate
        $page = intval($request->get('page', 1));
        $per_page = intval($request->get('per_page', 20));
        $model = $model->skip(($page - 1) * $per_page)->take($per_page);

        return [
            'pagination' => [
                'data' => $model->get(),
                'current_page' => $page,
                'per_page' => $per_page,
                'total' => $total,
            ]
        ];
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->allowed(__METHOD__);
        $model = new $this->model;
        return $model->findOrFail($id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestRequest $request)
    {
        $this->allowed(__METHOD__);
        $model = new $this->model;
        if ($model->rules) {
            $request->validate($model->rules);
        }
        $fields = $request->all();
        $item = $model->create($fields);
        return $item;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->allowed(__METHOD__);
        $model = new $this->model;
        if ($model->rules) {
            $request->validate($model->rules);
        }
        $fields = $request->all();
        $item = $model->findOrFail($id);
        $item->update($fields);
        return $item;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->allowed(__METHOD__);
        $model = new $this->model;
        $item = $model->findOrFail($id);
        $item->delete();
        return $item;
    }

    /**
     * Create options for specified resource if possible.
     *
     * @param  \App\Model  $model
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request)
    {
        $model = new $this->model;
        if ($model->option) {
            $valueField = array_keys($model->option)[0];
            $labelField = array_values($model->option)[0];
            foreach ($model->orderBy($labelField, 'ASC')->get([$valueField, $labelField]) as $item) {
                $options[] = ['value' => $item->$valueField, 'label' => $item->$labelField];
            }
            return $options;
        } else if (in_array('name', $model->fillable)) {
            $options = [];
            foreach ($model->all() as $item) {
                $options[] = ['value' => $item->id, 'label' => $item->name];
            }
            return $options;
        }
        return [];
    }

    public function allowed($method)
    {
        list($class, $name) = explode("::", $method);
        if (empty($this->allow) or in_array($name, $this->allow)) {
            return true;
        }
        throw new \Exception('Method not allowed');
    }
}
