<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    protected $table = "import_details";
    protected $guarded = [];

    public function ImportDetailToProduct()
    {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }
}
