<?php

namespace App\Console\Commands;

use App\Services\XmlService;
use Illuminate\Console\Command;
class ProductImport extends Command
{
    public $service;
    public $output;
    public function __construct()
    {
        $this->service = new XmlService;
        parent::__construct();
    }
    protected $signature = 'product:import {file=Produtos.xml}';
    protected $description = 'Read XML file and import products';
    public function handle()
    {
        $file_name = $this->argument('file');
        $this->service->importProductsByXml($this, $file_name);
        return 0;
    }
}
