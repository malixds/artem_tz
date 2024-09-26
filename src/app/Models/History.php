<?php

namespace App\Models;

use App\Enums\ActionEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $guarded = false;
    private static array $changingMethods = [ActionEnum::UPDATE, ActionEnum::PASSWORD];

    protected static function logAction($user, $action)
    {
        History::create([
            'model_id' => $user->id,
            'model_name' => $user->name,
//            'before' => in_array($action, self::$changingMethods)  ? json_encode($user->getOriginal()) : json_encode($user),
            'before' => $action === (ActionEnum::REGISTER || ActionEnum::RECOVER) ? null : json_encode($user->getOriginal()),
            'after' => in_array($action, self::$changingMethods) ? json_encode($user->getChanges()) : json_encode($user),
            'action' => $action,
        ]);
    }
}
