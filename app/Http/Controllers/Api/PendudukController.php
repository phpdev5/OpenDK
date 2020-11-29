<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PendudukRequest;
use App\Jobs\PendudukQueueJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendudukController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Insert penduduk ke OpenDK.
     * 
     * @param PendudukRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PendudukRequest $request)
    {
        // dispatch queue job penduduk
        PendudukQueueJob::dispatch($request->all());

        return response()->json([
            'message' => 'Proses sync data penduduk OpenSID sedang berjalan',
        ]);
    }

    public function test(Request $request)
    {
        return response(['penduduk' => DB::table('das_penduduk')->get()]);
    }
}