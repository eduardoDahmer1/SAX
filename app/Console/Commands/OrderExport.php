<?php

namespace App\Console\Commands;

use App\Services\XmlService;
use Illuminate\Console\Command;

class OrderExport extends Command
{
    public $service;
    public $output;

    public function __construct()
    {
        $this->service = new XmlService;
        parent::__construct();
    }
    protected $signature = 'order:export {total=10}';
    protected $description = 'Output last TOTAL orders to XML. Default is 10';
    public function handle()
    {
        $total = $this->argument('total');
        $this->service->exportOrdersByXml($this, $total);

        return 0;
    }
}
