<?php  
namespace Armincms\Arminpay; 
 
trait HasTrackingCode 
{  

    static public function bootHasTrackingCode()
    { 
        if(self::$checkTrackingCode ?? true) { 
            self::saved([static::class, 'ensureTrackingCode']);
        }
    }

	static public function ensureTrackingCode($model)
    {
        while (! $model->hasUniqueTrackingCode())
            $model->refershTrackingCode(); 
    }

    public function refershTrackingCode()
    {
        return $this->update(['tracking_code' => self::buildTrackingCode()]);
    }

    static public function buildTrackingCode()
    {
        return rand(9999999, 99999999);
    }

    public function hasUniqueTrackingCode()
    {  
        return $this->trackingCode() && $this->similarTrackingCodeCount() === 0;
    }

    public function similarTrackingCodeCount()
    {
        $query = self::whereNotNull('tracking_code')
                        ->where('tracking_code', $this->tracking_code)
                        ->where('id', '!=', $this->id);

        if(use_soft_deletes($this)) {
            $query->withTrashed();
        }

        return $query->count();
    }

    public function trackingCode()
    {  
        return $this->tracking_code;
    }
    
}
