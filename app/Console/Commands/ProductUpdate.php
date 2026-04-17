<?php

namespace App\Console\Commands;
use App\Models\Product;
use Illuminate\Console\Command;

class ProductUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:statusUpdate';

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
     * @return int
     */
    public function handle()
    {
        foreach (Product::with('vendor')->cursor() as $product) {
            if($product->vendor->status == 2){
                $product->deleted_at = date('Y-m-d H:i:s');
                $product->save();
            }
        }
    }
}
