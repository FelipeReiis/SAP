<?php

    namespace App\Services;
    use App\Models\Perito;

    class PeritoService{

        public function store(array $perito) :Perito
        {
            return Perito::create($perito);
        }

        public function show($id){
            $perito = Perito::findOrFail($id);
            return $perito;
        }

        public function update(Perito $perito, array $dados): Perito
        {
            $perito->update($dados);

            return $perito->refresh();
        }

    }

?>
