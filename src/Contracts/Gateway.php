<?php 
namespace Armincms\Arminpay\Contracts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface Gateway extends Trackable
{
	public function name(): string;  
	public function prepare(Tradable $tradable): self;  
	public function redirect(Request $request): RedirectResponse;
	public function verify(Request $request): bool;   
}