<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->type == 1) {
            $amount = '+ '.number_format($this->amount, 2) ;

            $title = 'From '.($this->source ? $this->source->name : '-');

        } else if($this->type == 2){
            $amount = '- '.number_format($this->amount, 2) ;

            $title = 'To '.($this->source ? $this->source->name : '-');
        }

        return [
            'trx_id' => $this->trx_id,
            'ref_no' => $this->ref_no,
            'type' => $this->type,
            'amount' => $amount.' MMK',
            'date_time' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s A'),
            'title' => $title,
            'description' => $this->description

        ];
    }
}
