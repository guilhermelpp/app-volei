<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlayerList extends Model
{
    use HasFactory;

    protected $with = ['items'];

    public function items()
    {
        return $this->hasMany(PlayerListItem::class)->orderBy('updated_at', 'asc')->orderBy('created_at', 'asc');
    }

    public static function findByDate($date)
    {
        return self::where(DB::raw("DATE_FORMAT(`created_at`, '%Y-%m-%d')"), '=', $date)->get();
    }
}
