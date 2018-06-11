<?php
/**
 * Created by PhpStorm.
 * User: johnson
 * Date: 2018/5/5
 * Time: 下午11:27
 */

namespace App\Console\Commands;
use App\Logic\Order\OrderLogic;
use App\Models\OrderModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $orderLogic = new OrderLogic();
        $detail = $orderLogic->getList();
        dump(DB::getQueryLog());
        dd($detail);
    }
}