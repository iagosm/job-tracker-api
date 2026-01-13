<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CandidaturaController extends Controller
{
    public function getAll()
    {
      $candidaturas = Candidatura::all()->toArray();
      return $this->sendSuccess('', Response::HTTP_OK, $candidaturas);
    }
}
