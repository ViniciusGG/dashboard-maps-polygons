<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndicatorRequest;
use App\Repositories\IndicatorRepository;
use Illuminate\Http\Request;

class InternalIndicatorsController extends Controller
{

  public function index(IndicatorRepository $indicatorRepository, Request $request)
  {
      $filter['columns'] = ['uuid', 'name', 'description', 'image','filter_id', 'status'];

      if($request->has('take')){
        $take = $request->input('take', 10);
        $filter['take'] = $take == -1 ? 10 : $take;
      }

    return response()->json($indicatorRepository->filter($filter), 200);
  }

  public function store(IndicatorRequest $request, IndicatorRepository $indicatorRepository)
  {
    $data = $request->validated();

    $indicator = $indicatorRepository->create($data);
    return response()->json($indicator->only('uuid','name','description','image','status'), 201);
  }

  public function update($uuid, IndicatorRequest $request, IndicatorRepository $indicatorRepository)
  {
    $data = $request->validated();

    $indicatorRepository->existsByUuid($uuid);

    $indicator = $indicatorRepository->updateByUuid($data,$uuid);
    return response()->json($indicator->only('uuid','name','description','image','status'), 201);
  }

}
