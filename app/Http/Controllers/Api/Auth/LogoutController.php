<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
          $user = Auth::user();
          $user->currentAccessToken()->delete();
          return $this->sendSuccess('Logout Realizado com sucesso!', Response::HTTP_OK);
    }
}
