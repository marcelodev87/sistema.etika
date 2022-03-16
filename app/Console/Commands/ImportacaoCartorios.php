<?php

namespace App\Console\Commands;

use App\NotaryAddress;
use Illuminate\Console\Command;

class ImportacaoCartorios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:importacaoCartorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (file_exists('./public/cartorios.json')) {
            $data = json_decode(file_get_contents('./public/cartorios.json'), true);
            foreach ($data as $d) {
                $array = [
                    'name' => $d['nome_cartorio'],
                    'zip' => $d['cep'],
                    'state' => $d['uf'],
                    'city' => $d['cidade'],
                    'neighborhood' => $d['bairro'],
                    'street' => $d['endereco'],
                    'complement' => $d['complemento'],
                    'observation_1' => $d['procedimentos'],
                    'email_1' => $d['email1'],
                    'email_2' => $d['email2'],
                    'phone_1' => $d['telefone1'],
                    'phone_2' => $d['telefone2'],
                ];
                NotaryAddress::create($array);
            }
            @unlink('./public/cartorios.json');
        }
    }
}
