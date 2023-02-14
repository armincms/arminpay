<?php

namespace Armincms\Arminpay\Http\Controllers;

use Armincms\Arminpay\Http\Requests\VerifyRequest;
use Armincms\Arminpay\Models\ArminpayTransaction;

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

        if ($request->expectsJson()) {
            return $transction->isSuccessed()
                ? array_merge($transction->serializeForDetailWidget($request), ['redirect' => $transction->success_callback])
                : response()->json(['errors' => ['Transaction failed']], 400);
        }

        return $transction->isSuccessed() ? redirect($transction->success_callback) : redirect($transction->fail_callback)->withErrors([
            'Transaction failed'
        ]);
    }
}
