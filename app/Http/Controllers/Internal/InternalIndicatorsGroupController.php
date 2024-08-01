<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Repositories\FilterRepository;
use App\Repositories\IndicatorRepository;
use Illuminate\Http\Request;

class InternalIndicatorsGroupController extends Controller
{

  public function index(FilterRepository $filterRepository, Request $request)
  {
      $filter['columns'] = ['uuid', 'name'];

      if($request->has('take')){
        $take = $request->input('take', 10);
        $filter['take'] = $take == -1 ? 10 : $take;
      }

    return response()->json($filterRepository->filter($filter), 200);
  }
}
