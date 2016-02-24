<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HipchatController extends Controller
{
    public function getCapabilities(Request $request) {
        $capabilities = [
            'name' => 'Workout Lottery Room Agent',
            'description' => 'This addon chooses people at a certain interval at random and makes them do certain random exercises',
            'key' => 'workout-lottery-room-agent',
            'links' => [
                'homepage' => 'https://www.workout-lottery.com',
                'self' => 'https://www.workout-lottery.com/api/hipchat/capabilities'
            ],
            'vendor' => [
                'name' => 'Frank Tiersch',
                'url' => 'https://www.ftiersch.de'
            ],
            'capabilities' => [
                'hipchatApiConsumer' => [
                    'fromName' => 'Workout Lottery',
                    'scopes' => [
                        'send_notification'
                    ]
                ],
                'installable' => [
                    'allowGlobal' => false,
                    'allowRoom' => true,
                    'callbackUrl' => route('api.hipchat.install'),
                    'uninstalledUrl' => route('api.hipchat.uninstall')
                ],
                'webhook' => [
                    "url" => route('api.hipchat.command.workoutdone'),
                    "pattern" => "^/([wW][oO][rR][kK][oO][uU][tT]|[wW][oO]) [dD][oO][nN][eE]",
                    "event" => "room_message",
                    "authentication" => "jwt",
                    "name" => "Workout Done"
                ]
            ]
        ];

        return response()->json($capabilities);
    }

    public function postInstall(Request $request) {

    }

    public function postUninstall(Request $request) {

    }

    public function postWorkoutDoneCommand(Request $request) {

    }
}
