<?php

    namespace App\Services;
    use App\Models\Servidor;

    class ServidorService{

        public function store(array $servidor){
            $servidor = Servidor::create($servidor);

            return true;
        }

        public function show($id){
            $servidor = Servidor::findOrFail($id);

            return $servidor;
        }

        public function update(array $servidor){
            $servidor = Servidor::findOrFail($servidor['id']);
            $servidor = $servidor->update($servidor);

            return true;
        }

    }

?>
