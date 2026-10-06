<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeritoRequest;
use App\Http\Requests\UpdatePeritoRequest;
use App\Http\Resources\PeritoResource;
use App\Models\Perito;
use App\Services\PeritoService;
use Illuminate\Http\Request;

class PeritoController extends Controller
{
    public function index()
    {
        $peritos = Perito::paginate(15);

        return PeritoResource::collection($peritos);
    }

    public function store(StorePeritoRequest $req, PeritoService $service){
        $perito = $service->store($req->validated());

        return response()->json([
            'message' => 'Perito cadastrado com sucesso',
            'data'    => new PeritoResource($perito),
        ], 201);
    }

    public function update(UpdatePeritoRequest $req, Perito $perito, PeritoService $service){
        $peritoAtualizado = $service->update($perito, $req->validated());

        return response()->json([
            'message' => 'Perito atualizado com sucesso',
            'data'    => new PeritoResource($peritoAtualizado),
        ]);
    }
}
