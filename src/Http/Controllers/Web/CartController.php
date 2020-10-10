<?php 
namespace Armincms\Arminpay\Http\Controllers\Web;

use Armin\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Cart;

class CartController extends Controller
{  
    /**
     * Make new Cart Item instance;
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return \Illuminate\Database\ELoquent\Model           
     */
    public function store(Request $request)
    {     
        $this->validate($request, $this->rules());

        $data = $this->fetchDataFromRequest($request);  

        if(! Cart::getContent()->has($data['id'])) { 
            Cart::add($data);
            $data['quantity'] = $data['quantity'] - 1;
        } 

        if($item = Cart::getContent()->get($data['id'])) { 
            $data['attributes'] = array_merge(
                $item->get('attributes')->toArray(), (array) $data['attributes']
            );
        } 

        Cart::update($data['id'], $data); 
        
        return $this->response($request); 

        
    }

    /**
     * Make new Cart Item instance;
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return \Illuminate\Database\ELoquent\Model           
     */
    public function remove(Request $request)
    {      
        Cart::remove($request->id); 

        return $this->response($request); 
    } 


    /**
     * Make new Cart Item instance;
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return \Illuminate\Database\ELoquent\Model           
     */
    public function response(Request $request)
    { 
        return [
            'items'         => Cart::getContent()->sortBy('id')->values(),
            'totalQuantity' => Cart::getTotalQuantity(),
            'total'         => Cart::getTotal(),
        ];
    }


    /**
     * Fetch required order data from request.
     * 
     * @param  \Illuminate\Http\Request $request 
     * @return array           
     */
    public function fetchDataFromRequest(Request $request)
    {
        return [
            'id'          => (int) $request->id,
            'name'        => $request->name,
            'price'       => floatval($request->price),
            'quantity'    => (int) $request->quantity === 0 ?: $request->quantity,
            'attributes'  => (array) $request->get('attributes', []),
        ];
    }
 
    /**
     * Cart Item data validation rules.
     * 
     * @return array
     */
    public function rules()
    {
    	return [ 
            'id'           => 'required|integer', 
            'name'         => 'required|string', 
            'price'        => 'required|integer',    
    	];
    } 
}