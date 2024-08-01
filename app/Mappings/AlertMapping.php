<?php 

namespace App\Mappings;

class AlertMapping
{
    protected $types;
    protected $status;

    public function __construct()
    {
        $this->types = config('settings.alerts.types');
        $this->status = config('settings.alerts.status');
    }

    public function getTypeIndex($typeName)
    {
        return array_search($typeName, $this->types);
    }

    public function getStatusIndex($statusName)
    {
        return array_search($statusName, $this->status);
    }
}
