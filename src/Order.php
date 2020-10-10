<?php  
namespace Armincms\Arminpay; 

use Illuminate\Database\Eloquent\{Model, SoftDeletes}; 
use Core\Crud\Concerns\HasCustomImage;
use Core\User\Contracts\Ownable;
use Core\User\Concerns\HasOwner; 
use Core\Crud\Contracts\Publicatable;
use Core\Crud\Concerns\Publishing;
use Core\Crud\Contracts\SearchEngineOptimize as SEO;
use Core\Crud\Concerns\SearchEngineOptimizeTrait as SEOTrait;
use Core\HttpSite\Contracts\Linkable;
use Core\HttpSite\Concerns\HasMultilingualPermalink as Permalink; 
use Core\HttpSite\Contracts\Hitsable;
use Core\HttpSite\Concerns\Visiting;
use Core\HttpSite\Concerns\IntractsWithSite; 
use Armincms\Arminpay\Contracts\Order as OrderContract;

class Order extends Model implements Ownable, OrderContract, Publicatable
{ 
	use SoftDeletes, HasOwner, IntractsWithSite, HasTrackingCode, Publishing;
 
    const PENDING  = "pending";
    const CANCELDE = "canceled";
    const FAILED   = "failed";
    const SUCCESS  = "success";
 
    protected $publishStatus = 'success';
	protected $guarded = [];
	protected $casts = [  
        'purchase_detail' => 'collection',
        'extra' => 'json',
    ];   

    public function setOrderDetailAttribute($details = [])
    {
        $this->attributes['order_detail'] = ProductCollection::make($details)->mapInto(Product::class);
    } 

    public function getOrderDetailAttribute($details)
    {
        return ProductCollection::make(json_decode($details, true))->mapInto(Product::class);
    }  

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'tracking_code', 'tracking_code')->latest();
    }

    public function customer()
    {
        return $this->morphTo();
    }

    public function component()
    { 
        return (new Components\Component); 
    }

    public function totalPrice(): float
    {
        return $this->order_detail->totalPrice();
    }

    public function unitPrice(): string
    {
        return $this->order_detail->currency();
    }    
}
