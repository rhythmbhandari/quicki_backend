<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Modules\Models\RiderLocation;

class UpdateOnlineRider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online_riders:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and updates the status of online active riders by checking their last updated time!';

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
  
        $this->updateOnlineRiders();
        return Command::SUCCESS;
    }


    public function updateOnlineRiders()
    {

        //Check and update the status of online rider locations
        $rider_locations = RiderLocation::where('status','active')->get();

        foreach($rider_locations as $rider_location)
        {
            $diffInSeconds = Carbon::now()->diffInSeconds(Carbon::parse($rider_location->updated_at));
            if( ($diffInSeconds > $rider_location->TIME_DIFFERENCE) )
            {
                $rider_location->status = "in_active";
                $rider_location->save();
                return 'not_available';
            }
        }

        
    }
}
