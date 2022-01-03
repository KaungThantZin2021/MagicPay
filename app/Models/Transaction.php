<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsto(User::class , 'user_id', 'id');

        //(User::class , 'user_id'                      , 'id')
        //             , Transaction_table-> user_id    , User_table->id
    }

    public function source(){
        return $this->belongsto(User::class , 'source_id', 'id');

        //(User::class , 'user_id'                        , 'id')
        //             , Transaction_table-> source_id    , User_table->id
    }
}
