<?php

namespace App\Services;

class PolygonService
{
    public $pointOnVertex = true;

  public function pointInPolygon($point, $polygon, $pointOnVertex = true)
    {
        if ($polygon == null) {
            return false;
        }
        $this->pointOnVertex = $pointOnVertex;

        $point = $this->pointStringToCoordinates($point);
        $vertices = array();
        foreach ($polygon as $vertex) {
            $vertices[] = $this->pointPolygonToCoordinates($vertex);
        }
        if ($this->pointOnVertex && $this->pointOnVertex($point, $vertices)) {
            return true;
        }

        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
                return true;
            }
            if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $point['x']) {
                    return true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        if ($intersections % 2 != 0) {
            return true;
        }

        return false;
    }

private function pointOnVertex($point, $vertices)
    {
        foreach ($vertices as $vertex) {
            if ($point == $vertex) {
                return true;
            }
        }
    }

private function pointStringToCoordinates($pointString)
    {
        $coordinates = explode(",", $pointString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

    private function pointPolygonToCoordinates($point)
    {
        return array("x" => (string)$point[0], "y" => (string)$point[1]);
    }
}
