<?php

namespace App;

use App\Contracts\Topical;
use Illuminate\Database\Eloquent\Model;

class Airline extends Model implements Topical
{
    protected $table = 'airlines';

    public function articles()
    {
        return $this->morphMany('App\Article', 'topic');
    }
}
