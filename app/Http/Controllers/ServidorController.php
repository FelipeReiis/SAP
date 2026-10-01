<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServidorRequest;
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
        $servidor = $service->store($req->valideted());
    }
}
