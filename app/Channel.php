<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function getRouteKeyName()
    {
        //looks on primary key , but here we need a slug
//        return parent::getRouteKeyName(); // TODO: Change the autogenerated stub

        return 'slug';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}