<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $unread_noti = auth()->user()->unreadNotifications()->count();

        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'account_number' => $this->wallet ? $this->wallet->account_number : '',
            'balance' => $this->wallet ? number_format($this->wallet->amount , 2): 0 ,
            'profile' => asset('img/profile.png'),
            'receive_qr_value' => $this->phone,
            'unread_noti' => $unread_noti
        ];
    }
}
