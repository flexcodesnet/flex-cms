<?php

namespace FXC\Base\Console\Commands;

use Carbon\Carbon;
use FXC\Base\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RefreshCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the exchange rate from https://api.apilayer.com/exchangerates_data and save it in database';

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
//        $currency_list = ['USD', 'EUR', 'SAR', 'AED', 'KWD', 'OMR', 'QAR', 'BHD', 'JOD', 'DZD', 'YER', 'TRY', 'GBP', 'CHF', 'CAD', 'AUD', 'CNY', 'RUB'];

        $endpoint = 'https://api.apilayer.com/exchangerates_data';
        $baseCurrency = 'USD';
        $apiKey = 'Gf3PPqnTe9oBsZDHOOmbxIzer8ALyXU7';

        // get value from api
        $todayDate = Carbon::now()->format('Y-m-d');
        $folderName = "currencies/$todayDate";
        if (!File::isDirectory(storage_path($folderName))) {
            Storage::makeDirectory($folderName, 775);
        }
        $fileName = "$folderName/currencies.json";
        if (!File::exists(storage_path("app/$fileName"))) {
            $data = file_get_contents("{$endpoint}/latest?base={$baseCurrency}&apikey={$apiKey}", true);
            Storage::put($fileName, $data);
        }
        $data = json_decode(file_get_contents(storage_path("app/$fileName")));
        $rates = $data->rates ?? null;
        $currencies = Currency::all();
        foreach ($currencies as $currency) {
            $slug = $currency->slug;
            $usd_exchange_rate = $rates->{$slug};
            $currency->exchange_rate = $usd_exchange_rate;
            $currency->save();
        }
        $today = Carbon::today();

        $this->info("currency prices successfully updated to today ($today) prices");
    }
}
