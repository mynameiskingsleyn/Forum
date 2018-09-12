<?php

namespace Forum;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use Traits\RecordsActivity;
    protected $guarded=[];
    //

    public function favorited()
    {
        return $this->morphTo();
    }
}
