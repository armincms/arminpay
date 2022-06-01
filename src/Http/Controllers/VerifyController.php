<?php

namespace Armincms\Arminpay\Http\Controllers;

use Armincms\Arminpay\Models\ArminpayTransaction; 
use Armincms\Arminpay\Http\Requests\VerifyRequest;

class VerifyController extends Controller
{  
    /**
     * Update the user profile
     * 
     * @return array
     */
    public function __invoke(VerifyRequest $request, $token)
    { 
        $transction = ArminpayTransaction::tracking($token)->firstOrFail();

        $transction->verify($request);

        return redirect($transction->callback_url);
    }
}
