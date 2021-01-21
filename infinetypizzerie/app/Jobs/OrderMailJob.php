<?php

namespace App\Jobs;

use stdClass;
use App\Mail\OrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class OrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The order object instance.
     *
     * @var Order
     */
    protected $orderDetails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderDetails)
    {
        //
        $this->orderDetails = $orderDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        Mail::to("corcko@gmail.com")->send(new OrderMail($this->orderDetails));
    }
}
