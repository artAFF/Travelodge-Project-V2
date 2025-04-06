<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    // Main notification coordinator methods
    public function sendNewIssueNotifications($travelodge, $issueData)
    {
        // $this->sendLineNotification($travelodge, $issueData);  // Disabled Line notification
        $this->sendDiscordNotification($travelodge, $issueData);
    }

    public function sendIssueUpdateNotifications($travelodge)
    {
        // $this->sendLineIssueNotification($travelodge);  // Disabled Line notification
        $this->sendDiscordIssueNotification($travelodge);
    }

    public function sendStatusUpdateNotifications($travelodge, $oldStatus, $newStatus)
    {
        // $this->sendLineStatusNotification($travelodge, $oldStatus, $newStatus);  // Disabled Line notification
        $this->sendDiscordStatusNotification($travelodge, $oldStatus, $newStatus);
    }

    public function sendAssigneeUpdateNotifications($travelodge, $oldAssignee, $newAssignee)
    {
        // $this->sendLineAssigneeNotification($travelodge, $oldAssignee, $newAssignee);  // Disabled Line notification
        $this->sendDiscordAssigneeNotification($travelodge, $oldAssignee, $newAssignee);
    }

    // Line Notification Methods - Disabled
    /*
    private function sendLineNotification($travelodge, $issueData)
    {
        $accessToken = env('LINE_NOTIFY_TOKEN');
        $messageUrl = 'https://notify-api.line.me/api/notify';

        $message = "New Issue Reported:\n";
        $message .= "  - Issue Category: " . ($travelodge->category->name ?? 'N/A') . "\n";
        $message .= "  - Detail: " . $travelodge->detail . "\n";
        $message .= "  - Remarks: " . ($travelodge->remarks ?? "No remarks") . "\n";
        $message .= "  - Department: " . ($travelodge->department->name ?? 'N/A') . "\n";
        $message .= "  - Hotel: " . ($travelodge->hotel->hotel_code ?? 'N/A') . "\n";
        $message .= "  - Attachment: " . ($travelodge->file_path ? 'Yes' : 'No') . "\n";
        $message .= "  - Assignee: " . ($travelodge->assignee->name ?? 'Unassigned') . "\n";
        $message .= "  - Status: " . ($travelodge->status == 0 ? 'In-progress' : 'Done');

        $this->sendLineMessage($messageUrl, $accessToken, $message);
    }

    private function sendLineIssueNotification($travelodge)
    {
        $accessToken = env('LINE_NOTIFY_TOKEN');
        $messageUrl = 'https://notify-api.line.me/api/notify';

        $message = "Update Issue Reported:\n";
        $message .= "  - ID Issue: " . $travelodge->id . "\n";
        $message .= "  - Issue Category: " . ($travelodge->category->name ?? 'N/A') . "\n";
        $message .= "  - Detail: " . $travelodge->detail . "\n";
        $message .= "  - Remarks: " . ($travelodge->remarks ?? "No remarks") . "\n";
        $message .= "  - Department: " . ($travelodge->department->name ?? 'N/A') . "\n";
        $message .= "  - Hotel: " . ($travelodge->hotel->hotel_code ?? 'N/A') . "\n";
        $message .= "  - Assignee: " . ($travelodge->assignee->name ?? 'Unassigned') . "\n";
        $message .= "  - Status: " . ($travelodge->status == 0 ? 'In-progress' : 'Done');

        $this->sendLineMessage($messageUrl, $accessToken, $message);
    }

    private function sendLineStatusNotification($travelodge, $oldStatus, $newStatus)
    {
        $accessToken = env('LINE_NOTIFY_TOKEN');
        $messageUrl = 'https://notify-api.line.me/api/notify';

        $message = "Status Update:\n";
        $message .= "  - ID: " . $travelodge->id . "\n";
        $message .= "  - Detail: " . $travelodge->detail . "\n";
        $message .= "  - Old Status: " . ($oldStatus == 0 ? 'In-process' : 'Done') . "\n";
        $message .= "  - New Status: " . ($newStatus == 0 ? 'In-process' : 'Done');

        $this->sendLineMessage($messageUrl, $accessToken, $message);
    }

    private function sendLineAssigneeNotification($travelodge, $oldAssignee, $newAssignee)
    {
        $accessToken = env('LINE_NOTIFY_TOKEN');
        $messageUrl = 'https://notify-api.line.me/api/notify';

        $message = "Assignee Update:\n";
        $message .= "  - ID: " . $travelodge->id . "\n";
        $message .= "  - Detail: " . $travelodge->detail . "\n";
        $message .= "  - Remarks: " . ($travelodge->remarks ?? "No remarks") . "\n";
        $message .= "  - Old Assignee: " . $oldAssignee . "\n";
        $message .= "  - New Assignee: " . $newAssignee;

        $this->sendLineMessage($messageUrl, $accessToken, $message);
    }

    private function sendLineMessage($messageUrl, $accessToken, $message)
    {
        $client = new Client();

        try {
            $response = $client->post($messageUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'message' => $message,
                ],
            ]);
            return $response->getStatusCode() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
    */

    // Discord Notification Methods
    private function sendDiscordNotification($travelodge, $issueData)
    {
        $embed = [
            'title' => 'New Issue Reported',
            'color' => 15158332,
            'fields' => [
                [
                    'name' => 'Issue Category',
                    'value' => $travelodge->category->name ?? 'N/A',
                    'inline' => true
                ],
                [
                    'name' => 'Detail',
                    'value' => $travelodge->detail,
                    'inline' => false
                ],
                [
                    'name' => 'Remarks',
                    'value' => $travelodge->remarks ?? 'No remarks',
                    'inline' => false
                ],
                [
                    'name' => 'Department',
                    'value' => $travelodge->department->name ?? 'N/A',
                    'inline' => true
                ],
                [
                    'name' => 'Hotel',
                    'value' => $travelodge->hotel->hotel_code ?? 'N/A',
                    'inline' => true
                ],
                [
                    'name' => 'Attachment',
                    'value' => $travelodge->file_path ? 'Yes' : 'No',
                    'inline' => true
                ],
                [
                    'name' => 'Assignee',
                    'value' => $travelodge->assignee->name ?? 'Unassigned',
                    'inline' => true
                ],
                [
                    'name' => 'Status',
                    'value' => $travelodge->status == 0 ? 'In-progress' : 'Done',
                    'inline' => true
                ]
            ],
            'timestamp' => date('c')
        ];

        $this->sendDiscordMessage($embed);
    }

    private function sendDiscordIssueNotification($travelodge)
    {
        $embed = [
            'title' => 'Update Issue Reported',
            'color' => 15158332,
            'fields' => [
                ['name' => 'ID Issue', 'value' => $travelodge->id, 'inline' => true],
                ['name' => 'Issue Category', 'value' => $travelodge->category->name ?? 'N/A', 'inline' => true],
                ['name' => 'Detail', 'value' => $travelodge->detail, 'inline' => false],
                ['name' => 'Remarks', 'value' => $travelodge->remarks ?? 'No remarks', 'inline' => false],
                ['name' => 'Department', 'value' => $travelodge->department->name ?? 'N/A', 'inline' => true],
                ['name' => 'Hotel', 'value' => $travelodge->hotel->hotel_code ?? 'N/A', 'inline' => true],
                ['name' => 'Assignee', 'value' => $travelodge->assignee->name ?? 'Unassigned', 'inline' => true],
                ['name' => 'Status', 'value' => $travelodge->status == 0 ? 'In-progress' : 'Done', 'inline' => true],
            ],
            'timestamp' => date('c')
        ];

        $this->sendDiscordMessage($embed);
    }

    private function sendDiscordStatusNotification($travelodge, $oldStatus, $newStatus)
    {
        $embed = [
            'title' => 'Status Update',
            'color' => 3447003,
            'fields' => [
                ['name' => 'ID', 'value' => $travelodge->id, 'inline' => true],
                ['name' => 'Detail', 'value' => $travelodge->detail, 'inline' => false],
                ['name' => 'Old Status', 'value' => $oldStatus == 0 ? 'In-process' : 'Done', 'inline' => true],
                ['name' => 'New Status', 'value' => $newStatus == 0 ? 'In-process' : 'Done', 'inline' => true],
            ],
            'timestamp' => date('c')
        ];

        $this->sendDiscordMessage($embed);
    }

    private function sendDiscordAssigneeNotification($travelodge, $oldAssignee, $newAssignee)
    {
        $embed = [
            'title' => 'Assignee Update',
            'color' => 5763719,
            'fields' => [
                ['name' => 'ID', 'value' => $travelodge->id, 'inline' => true],
                ['name' => 'Detail', 'value' => $travelodge->detail, 'inline' => false],
                ['name' => 'Remarks', 'value' => $travelodge->remarks ?? 'No remarks', 'inline' => false],
                ['name' => 'Old Assignee', 'value' => $oldAssignee, 'inline' => true],
                ['name' => 'New Assignee', 'value' => $newAssignee, 'inline' => true],
            ],
            'timestamp' => date('c')
        ];

        $this->sendDiscordMessage($embed);
    }

    private function sendDiscordMessage($embed)
    {
        $webhookUrl = env('DISCORD_WEBHOOK_URL');
        $client = new Client();

        try {
            $response = $client->post($webhookUrl, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'embeds' => [$embed]
                ]
            ]);
            return $response->getStatusCode() === 204;
        } catch (\Exception $e) {
            return false;
        }
    }
}
