<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
use Closure;

class TokenMiddlewareOld
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->header('authorization')) {
            return response(['error' => 'Unauthorized Access', 'msg' => $request->header('authorization')]);
        }

        $authToken =  $request->header('authorization');
        $accessUserId = $request->header('accessUserId');
        $redisKey = 'red_access_token_' . $accessUserId;
        $storedToken = Redis::exists($redisKey) ? Redis::get($redisKey) : '';

        if ($storedToken && $authToken == $storedToken) {
            return $next($request);
        } elseif ($storedToken && $this->guzzleTest($authToken)) {
            Redis::set($redisKey, $authToken, 'EX', 28800);
            return $next($request);
        } elseif (!$storedToken && $this->guzzleTest($authToken)) {
            Redis::set($redisKey, $authToken, 'EX', 28800);
            return $next($request);
        } else {
            return response([
                'success' => false,
                'msg' => 'Unauthorized Access. Invalid request token.'
            ]);
        }

        return response(['error' => 'Unauthorized Access']);
    }

    private function guzzleTest($token)
    {
        try {
            $client = new Client();
            $response = $client->request('GET', 'http://auth-service.local/token-verification', [
                'headers' => [
                    'Authorization' => $token
                ]
            ]);
        } catch (\Exception $ex) {
            return false;
        }

        if (isset($response) && $response->getStatusCode() == 200) {
            return true;
        }

        return false;
    }
}
