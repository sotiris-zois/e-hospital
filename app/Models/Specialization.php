<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use VPominchuk\ModelUseIndex;

class Specialization extends Model
{
    use ModelUseIndex;

    protected $fillable = [
        'code',
        'grouping',
        'classification',
        'specialization',
        'definition'
    ];


}
