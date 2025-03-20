<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class UpdateProductStatus extends Command
{
    protected $signature = 'products:update-status';
    protected $description = 'Atualiza o status dos produtos com base em critérios definidos';

    public function handle()
    {
        try {
            // Definindo a URL gerada pelo helper 'asset' para a imagem "noimage.png"
            $noImageUrl = asset('assets/images/noimage.png');

            // Atualiza o status dos produtos na tabela
            DB::statement(<<<SQL
            UPDATE `products`
            SET `status` = CASE
                -- Se o produto atender a todas as condições, o status será 1 (ativo)
                WHEN (`photo` IS NOT NULL AND `photo` != '' AND `photo` != '$noImageUrl')
                     AND (`thumbnail` IS NOT NULL AND `thumbnail` != '' AND `thumbnail` != '$noImageUrl')
                     AND `stock` > 0 
                     AND (`price` > 0 OR `price` IS NOT NULL) THEN 1
                -- Caso contrário, o status será 0 (inativo)
                ELSE 0
            END;
            SQL);

            $this->info('Status dos produtos atualizado com sucesso.');
        } catch (\Exception $e) {
            $this->error('Erro ao atualizar o status: ' . $e->getMessage());
        }
    }
}
