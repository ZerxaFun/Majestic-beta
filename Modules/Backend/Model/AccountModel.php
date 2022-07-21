<?php

namespace Modules\Backend\Model;


use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;


class AccountModel extends Model
{
    protected $table = 'account';


    public function table(): Builder
    {
        return Capsule::table($this->table);
    }

}