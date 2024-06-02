<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// use Request;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Utils\{
    Authorize,
    ResponseHandler
};

use App\Models\{
    Constants,
    User,
};

use Exception;
use Illuminate\Support\Facades\File;



class CreateOrderMailJob implements ShouldQueue
{



    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $getPriceDetails;
    protected $orderID;
    protected $TotalPrice;
    protected $subject;


    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $const;

    // public function __construct($getPriceDetails,$orderID,$TotalPrice,$subject)
    public function __construct()
    {


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Request $request)
    {



    }
}
