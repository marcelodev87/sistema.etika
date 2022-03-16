<?php

use Illuminate\Database\Seeder;
use App\InternalTask;
use Illuminate\Support\Str;

class TasksTableSeeder extends Seeder
{

    public function run()
    {
        $tarefas = [
            ['Elaboração de Contrato', 0.00],
            ['Boleto', 0.00],
            ['Contato com Cartório', 0.00],
            ['Viabilidade', 0.00],
            ['Conferir Viabilidade', 0.00],
            ['DBE', 0.00],
            ['Conferir DBE', 0.00],
            ['Enviar para Análise do Cliente', 0.00],
            ['Elaborar Documentos', 0.00],
            ['Enviar documentos para registro', 0.00],
            ['Exigências', 0.00],
            ['Agendamento Receita Federal', 0.00],
            ['Alteração de Documentos', 0.00],
            ['Cadastro no Sistema Contábil', 0.00],
            ['Certificado Digital', 0.00],
            ['Procuração Eletrônica', 0.00],
            ['Recalculo de Guia', 0.00],
            ['Agenda Telefônica', 0.00],
            ['Relatório de Situação Fiscal', 0.00],
            ['Protocolo', 0.00],
            ['Fazer Contato', 0.00],
        ];
        foreach ($tarefas as $tarefa) {
            InternalTask::create([
                'name' => $tarefa[0],
                'slug' => Str::slug($tarefa[0]),
                'price' => $tarefa[1]
            ]);
        }
    }
}
