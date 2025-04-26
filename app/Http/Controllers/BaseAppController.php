<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;

class BaseAppController extends Controller implements HasMiddleware
{

    public $user;
    public static function middleware(): array{
        return [
            'auth'
        ];
    }

    public function __construct(){
        $this->user = User::findOrFail(Auth::user()->id);
    }
}
