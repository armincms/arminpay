<?php  
namespace Armincms\Arminpay; 

use Armin\Models\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;  
use Armincms\Arminpay\Contracts\Tradable; 
use Illuminate\Http\Request;

class Transaction extends Model implements Tradable 
{ 
	use SoftDeletes;
 
	protected $guarded = [];
	protected $casts = [
		'payment_date' => 'datetime',
		'extra' => 'json',
	];   

	public function amount(): float
	{
		return floatval($this->amount);
	} 

	public function currency(): string
	{
		return (string) $this->currency;
	} 

	public function callback() : string
	{
		return route('arminpay.transaction.verify', $this->trackingCode());
	} 

	public function verifyPath()
	{
		return route('arminpay.transaction.verify', $this);
	}

	public function referenceTo(string $referenceNumber) : Tradable
	{
		$this->reference_number = $referenceNumber;

		return $this;
	}

	public function trackingCode()
	{
		return $this->tracking_code;
	}

	public function referenceNumber()
	{
		return $this->reference_number;
	} 

	public function order()
	{
		return $this->belongsTo(Order::class, 'tracking_code', 'tracking_code');
	}

	public function gateway()
	{  
		return app('arminpay.gateway')->resolve($this->gateway);
	} 

	public function finish(Request $request)
	{  
		$status = $this->paymentVerified($request)? Order::SUCCESS : Order::FAILED; 

		return tap($this->markAs($status), function($transaction) {  
			$transaction->update([
				'payment_date' => (string) now(),
				'reference_number' => $transaction->verified() 
										 ? $this->gateway()->trackingCode() : null
			]); 
		}); 
	} 

	protected function paymentVerified(Request $request)
	{
		return (boolean) optional($this->gateway())->verify($request);
	}

	protected function markAs(string $status)
	{
		$this->update(compact('status'));

		return $this;
	}

	protected function markedAs(string $status)
	{ 
		return $this->status === $status;
	}

	public function verified()
	{
		return $this->markedAs(Order::SUCCESS);
	}

	public function failed()
	{
		return $this->markedAs(Order::FAILED);
	}  
}
