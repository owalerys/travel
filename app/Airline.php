<?php

namespace App;

use App\Contracts\Topical;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Airline
 * @package App
 */

class Airline extends Model implements Topical
{
    protected $table = 'airlines';

    public function articles()
    {
        return $this->morphMany(Article::class, 'topic');
    }
}
