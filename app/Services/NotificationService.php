<?php

namespace App\Services;

use App\Mail\NewMatchFound;
use App\Mail\TransferRequestAccepted;
use App\Mail\TransferRequestDeclined;
use App\Mail\TransferRequestReceived;
use App\Models\EmployeeProfile;
use App\Models\TransferRequest;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendTransferRequestReceived(TransferRequest $request): void
    {
        Mail::to($request->requestee->user->email)
            ->queue(new TransferRequestReceived($request));
    }

    public function sendTransferRequestAccepted(TransferRequest $request): void
    {
        Mail::to($request->requester->user->email)
            ->queue(new TransferRequestAccepted($request));
    }

    public function sendTransferRequestDeclined(TransferRequest $request): void
    {
        Mail::to($request->requester->user->email)
            ->queue(new TransferRequestDeclined($request));
    }

    public function sendNewMatchFound(EmployeeProfile $matchedProfile, EmployeeProfile $recipientProfile): void
    {
        Mail::to($recipientProfile->user->email)
            ->queue(new NewMatchFound($matchedProfile, $recipientProfile));
    }

    public function notifyMatchesForProfile(EmployeeProfile $profile): void
    {
        if (!$profile->is_available_for_transfer || $profile->probation_status !== 'completed') {
            return;
        }

        $matchingService = new TransferMatchingService();
        $matches = $matchingService->getMatchesForProfile($profile);

        foreach ($matches as $match) {
            // Only notify if it's a mutual match
            if ($matchingService->isMutualMatch($profile, $match)) {
                // Notify the matched user about this profile
                $this->sendNewMatchFound($profile, $match);
            }
        }
    }
}
