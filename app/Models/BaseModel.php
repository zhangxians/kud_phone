<?php
namespace App\Models;

use App\Traits\Model\BaseModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    use SoftDeletes;
    use BaseModelTrait;

}
