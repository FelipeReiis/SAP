<?php

    namespace App\Services;
    use App\Models\Servidor;

    class ServidorService{

        public function store(array $servidor){
            $servidor = Servidor::create($servidor);

            return Servidor::create($servidor);
        }

        public function show($id){
            $servidor = Servidor::findOrFail($id);

            return $servidor;
        }

        public function update(Servidor $servidor, array $dados): Servidor
        {
            $servidor->update($dados);

            return $servidor->refresh();
        }

    }

?>
