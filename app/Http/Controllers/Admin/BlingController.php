<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Bling;
use Illuminate\Http\Request;

class BlingController extends Controller
{
    private Bling $bling;

    public function __construct()
    {
        $this->bling = new Bling();
    }

    public function authenticate()
    {
        return $this->bling->authorize();
    }

    public function setTokens(Request $request)
    {
        if (!$this->bling->isValidState($request->get('state'))) {
            abort(403);
        }

        $this->bling->generateTokens($request->get('code'));

        return redirect()->route('admin.dashboard');
    }
}
