<?php

namespace App\Repositories;

use App\Models\Filter;

class FilterRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Filter::class);
    }

    public function getFilterByName($name)
    {
        $query = $this->model->newQuery();
        $query->where('name', $name);

        $filter = $query->firstOrFail();

        return $filter->id;
    }


}
