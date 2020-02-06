#phptest-1.2


install.. 
```bash 
composser upgrade
```


.env

```php

DB_CONNECTION=mysql
DB_HOST=host
DB_PORT=3306
DB_DATABASE={database}
DB_USERNAME={userName}
DB_PASSWORD={password}

```


##http server option 1. 


apache vhost.conf

```apache


<VirtualHost *:80>
    ServerAdmin webmaster@exchange.com
    ServerName exchange.com
    DocumentRoot /var/www/exchange/public
    <Directory "/var/www/exchange/public">
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
            Require all granted
            ReWriteEngine On
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.exchange.vm.log
    CustomLog ${APACHE_LOG_DIR}/access.exchange.vm.log combined
</VirtualHost>


```

##https server option 2.

```bash

php artisan serve --port=8080

```

Simple eloquent migration: 

```php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Exchange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchange', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('from', 3);
            $table->string('to', 3);
            $table->string('rate', 10,5);
            $table->timestamps();
            $table->softDeletes();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

```

simple model : app/Exchange.php 


```php

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    const TYPES = [
        "CAD",  
        "JPY", 
        "USD", 
        "GBP",
        "EUR", 
        "RUB", 
        "HKD",
        "CHF" 
    ];
    use SoftDeletes;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $table = 'exchange';
    protected $primaryKey = 'id';
}
    

```

The route (routes/api.php

```php 


Route::get('/exchange/info', 'ExchangeController@getInfo');
Route::get('/exchange/cache/clear', 'ExchangeController@getCacheClear');
Route::get('/exchange/{amount}/{from}/{to}', 'ExchangeController@getExchange');

```


###app/Controller/Trail/exchangeHelper 

```php
<?php

namespace App\Traits;

use App\Exchange;

trait exchangeHelper 
{
    /*
     *
     *
     */
     private $notSupported =[];
    /*
     *
     *
     */
     private $error = false;
    /*
     *
     *
     */
     private $fromCache = 1;
    /**
     * Check outdated data and tigger the cache  update.
     *
     * @return  Integer
     */
    private function update (){
        $exchanges = \App\Exchange::where(
            'updated_at', 
            '<', 
            date('Y-m-d H:i:s',strtotime('-1 minute'))
        )->select(
            'from'
        )->groupBy('from')->get();
        if(count($exchanges)>0)
            for($i = 0 ; count($exchanges) > $i ; $i++)
                $this->getFormApi($exchanges[$i]->form);
        return count($exchanges);
    }
    /**
     * Save or update currency pair.
     * @param string {$from}
     * @param string {$to}
     * @param double {$rate}
     * @return integer 
     */
    private function save($from, $to, $rate){
        $exchange = $this->check($from,$to);
        if(!$exchange){
            $exchange = new \App\Exchange();
        }
        $exchange->from = $from;
        $exchange->to = $to;
        $exchange->rate = $rate;
        $exchange->save();
        return $exchange->id;
    }
    /**
     * Execute the console command.
     * @param string {$from}
     * @param string {$to}
     * @return mixed
     */
    public function check($from, $to){
        $exchange = \App\Exchange::where(function ($query) use ($to, $from) {
            $query->where('to', '=', $to)
                   ->where('from', '=', $from);
            })->orWhere(function ($query) use ($from, $to){
            $query->where('to', '=', $from)
                   ->where('from', '=', $to);

            })->first();
        if(isset($exchange->id))
           return $exchange;
        return false;
    }
    /*
     * @param string {$from}
     * @param string {$to}
     * @param double {$amount}
     * @return float 
     */
    private function exchange($from, $to, $amount){
        if(!in_array($from, \App\Exchange::TYPES, true))
          $this->notSupported[]=$from;
        if(!in_array($to, \App\Exchange::TYPES, true))
          $this->notSupported[]=$to;
        if(count($this->notSupported)>0) 
            return false;
        $exchange = $this->check($from, $to);
        if( $exchange == false ){
            $this->getFormApi($from);
            $exchange = $this->check($from, $to);
        }
        
        if($from == $exchange->from){
            return round(($amount * $exchange->rate), 2);
        }
        if($to == $exchange->from){
            return round(($amount / $exchange->rate), 2);
        }
        return false;

    }
    /*
     * @return void
     */
    private function getFormApi($currency="NONE"){
        $this->fromApi = 0;
        $req_url = 'https://api.exchangeratesapi.io/latest?base='.$currency;
        $response_json = file_get_contents($req_url);
        if(false !== $response_json) {
            try {
                 $response = json_decode(
                     $response_json,
                     true
                 );
                 foreach(\App\Exchange::TYPES as $type){
                     if($type == $currency)
                         continue;
                     $this->save(
                         $currency,
                         $type,
                         round(
                            $response['rates'][$type], 
                            5
                         )
                     ); 
                 }
                 return true;
            }catch(Exception $e) {
 
            }

        }
    }
    private function cacheClear(){
        \App\Exchange::truncate();
    }
}


```


And the controller: 

```php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ExchangeHelper;

class ExchangeController extends Controller
{
    use exchangeHelper;
    /*
     * @param string {$from}
     * @param string {$to}
     * @param double {$amount}
     * @return  
     */
    public function getExchange ($amount, $from, $to){
        $result = $this->exchange($from, $to, $amount);
        if($result == false)
             return response()->json([
                 "error" =>  1,
                 "msg"   =>   "currency code ". implode(",",$this->notSupported)." not supported"
              ]);
        return response()->json([
            "error"     => 0,
            "amount"    => $result,
            "fromCache" => $this->fromCache

        ]);
    }

    public function getCacheClear (){
        $this->cacheClear();    
        return response()->json([
            "error" => 0,
            "msg" => "OK"
        ]);
    }

    public function getInfo (){
        return response()->json([
            "error" => 0,
            "msg" => "API writen by 501dy"
        ]);
    }
}


```



bonus task 1 : app/Console/Kernel.php line 27



bonus task 2 : included to the main logic.
