<?php

use Illuminate\Database\Seeder;
use App\InternalProcess;
use Illuminate\Support\Str;

class ProcessesTableSeeder extends Seeder
{

    public function run()
    {
        $processos = [
            ['Elaboração de Estatuto, Ata e Pedido de CNPJ', 600.00],
            ['Abertura de Igreja no Rio de Janeiro - Taxas do Cartório Incluso', 2000.00],
            ['Abertura de Igreja no Nova Iguaçu - Taxas do Cartório Incluso', 1800.00],
            ['Abertura de Igreja no Belford Roxo - Taxas do Cartório Incluso', 1700.00],
            ['Elaboração de Ata', 200.00],
            ['Pedido de CNPJ', 200.00],
            ['Abertura de Empresa', 1500.00],
            ['Alteração Contratual - ME', 1000.00],
            ['Alteração Contratual - Regime Normal', 1300.00],
            ['Alvará de Funcionamento', 0.00],
            ['Vigilância Sanitária', 0.00],
            ['Bombeiros', 0.00],
        ];
        foreach ($processos as $processo) {
            InternalProcess::create([
                'name' => $processo[0],
                'slug' => Str::slug($processo[0]),
                'price' => $processo[1],
            ]);
        }

    }
}
