<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductStatus extends Command
{
    protected $signature = 'products:update-status';
    protected $description = 'Atualiza o status dos produtos com base em critérios definidos';

    public function handle()
    {
        try {
            // Atualiza o status dos produtos na tabela
            DB::statement(<<<SQL
            UPDATE `products`
            SET `status` = CASE
                WHEN (`photo` IS NOT NULL AND `photo` != '' AND `photo` != 'noimage.png') 
                     AND `stock` > 0 
                     AND (`price` > 0 OR `price` IS NOT NULL) THEN 1
                ELSE 0
            END;
            SQL);

            $this->info('Status dos produtos atualizado com sucesso.');
        } catch (\Exception $e) {
            $this->error('Erro ao atualizar o status: ' . $e->getMessage());
        }
    }
}
