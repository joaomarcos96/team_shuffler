<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['name', 'level', 'goalkeeper'];

    /**
     * Validation rules for creating a player
     *
     * @var array
     */
    public static $createValidationRules = [
        'name' => 'required',
        'level' => 'required|between:1,5|integer',
        'goalkeeper' => 'nullable|boolean'
    ];

    /**
     * Validation rules for updating a player
     *
     * @var array
     */
    public static $updateValidationRules = [
        'name' => 'required',
        'level' => 'required|between:1,5|integer',
        'goalkeeper' => 'nullable|boolean'
    ];

    /**
     * @param array $confirmedPlayersId
     * @return Collection
     */
    public static function getConfirmedPlayers($confirmedPlayersId)
    {
        return self::whereIn('id', $confirmedPlayersId)
            ->orderBy('level', 'ASC')
            ->get();
    }
}
