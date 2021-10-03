<?php

namespace Pedreiro\Elements\ContentTypes;

use Illuminate\Support\Facades\DB;

class Coordinates extends BaseType
{
    /**
     * @return \Illuminate\Database\Query\Expression|null
     */
    public function handle()
    {
        if (empty($coordinates = $this->request->input($this->row->field))) {
            return;
        }
        //DB::connection()->getPdo()->quote won't work as it quotes the
        // lat/lng, which leads to wrong Geometry type in POINT() MySQL constructor
        $lat = (float) $coordinates['lat'];
        $lng = (float) $coordinates['lng'];

        return DB::raw("ST_GeomFromText('POINT({$lng} {$lat})')");
    }
}
