<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Compare extends CachedModel
{
    /**
     * Armazena os itens em cache.
     */
    public $items = [];

    /**
     * Construtor otimizado para evitar carregar dados desnecessários.
     * Usa cache ou inicializa com dados existentes.
     */
    public function __construct($oldCompare = null)
    {
        parent::__construct();

        // Carregar dados de cache diretamente ou inicializar com dados passados.
        $this->items = $this->loadItemsFromCache($oldCompare);
    }

    /**
     * Carrega os itens de comparação de um cache.
     * Se não encontrar, retorna um array vazio.
     */
    protected function loadItemsFromCache($oldCompare)
    {
        if ($oldCompare) {
            return $oldCompare->items ?? [];
        }

        return Cache::get('compare_items', []);
    }

    /**
     * Adiciona um item ao comparador.
     * Aproveita para armazenar no cache.
     */
    public function add($item, $id)
    {
        // Adiciona item ao array
        $this->items[$id] = [
            'ck' => 1,
            'item' => $item,
        ];

        // Atualiza o cache com os novos itens
        $this->updateCache();
    }

    /**
     * Remove um item da comparação.
     * Aproveita para atualizar o cache.
     */
    public function removeItem($id)
    {
        unset($this->items[$id]);

        // Atualiza o cache após remoção
        $this->updateCache();
    }

    /**
     * Verifica se um item já está comparado.
     */
    public function isItemCompared($id): bool
    {
        return isset($this->items[$id]);
    }

    /**
     * Atualiza os itens no cache.
     * Isso evita múltiplas leituras do banco de dados ou reconstruções repetidas.
     */
    protected function updateCache()
    {
        Cache::put('compare_items', $this->items, now()->addMinutes(60)); // Armazenamento em cache por 60 minutos
    }
}
