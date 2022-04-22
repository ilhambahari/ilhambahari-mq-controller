<?php

namespace Qoligo\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Arr;

class MqEventSubscriberController extends Controller
{
    public function listener(Request $request)
    {
        try {
            if ($this->authWithToken($request)) {
                if (isset($request->payload) && isset($request->event)) {
                    $payload = $request->payload;
                    $handler = $this->getEventHandler($request->event);

                    if ($handler) {
                        call_user_func($handler . '::' . 'dispatch', $payload);
                    }
    
                    return response()->json(['success' => true, 'message' => 'Event processed successfully'], 200);
    
                } else {
                    throw new Exception("Request must contain a valid payload", 1);
                }   
            } else {
                throw new Exception("Authentication failed", 0);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function authWithToken(Request $request)
    {
        try {
            $auth_token = env('RABBITMQ_HTTP_AUTH_TOKEN', null);

            if (!is_null($auth_token) && $auth_token !== '') {

                if ($request->bearerToken() !== '') {
                    return ($auth_token == $request->bearerToken());
                } else {
                    throw new Exception('Auth token is missing', 0);
                }
                
            } else {
                // AUTH IS IGNORED
                return true;
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function getEventHandler($event)
    {
        try {
            return Arr::get(config('eventRouting.subscriber', []), $event, false);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
