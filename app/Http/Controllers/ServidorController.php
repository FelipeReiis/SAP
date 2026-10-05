<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServidorRequest;
use App\Http\Requests\UpdateServidorRequest;
use App\Http\Resources\ServidorResource;
use App\Models\Servidor;
use App\Services\ServidorService;
use Illuminate\Http\Request;

class ServidorController extends Controller
{
    public function index()
    {
        $servidores = Servidor::paginate(15);

        return ServidorResource::collection($servidores);
    }

    public function store(StoreServidorRequest $req, ServidorService $service)
    {
        $servidor = $service->store($req->validated());
        return response()->json([
            'message' => 'Servidor cadastrado com sucesso',
            'data'    => new ServidorResource($servidor),
        ], 201);
    }

    public function update(UpdateServidorRequest $req, Servidor $servidor, ServidorService $service){
        $servidorAtualizado = $service->update($servidor, $req->validated());

        return response()->json([
            'message' => 'Servidor atualizado com sucesso',
            'data'    => new ServidorResource($servidorAtualizado),
        ], 200);
    }
}
