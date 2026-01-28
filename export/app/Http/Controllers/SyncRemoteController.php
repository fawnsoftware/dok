<?php

namespace App\Http\Controllers;

use App\Services\SyncGitHubService;
use Illuminate\Http\Request;

class SyncRemoteController extends Controller
{
    /**
     * Handles the sync process via the control pnel
     *
     * @param  Request  $request  The current request.
     */
    public function sync(Request $request)
    {

        if (! $request->input('resource')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No resource provided',
            ]);
        }

        if (! $request->input('source')) {
            return response()->json([
                'status' => 'error',
                'message' => 'No source provided',
            ]);
        }

        if ($request->input('source') == 'github') {
            return $this->syncGitHub($request->input('resource'));
        }
    }

    public function syncGitHub(string $resource)
    {
        try {
            (new SyncGitHubService)->sync($resource);

            return response()->json([
                'status' => 'success',
                'message' => 'Documentation synced successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
