<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Requests\TransferFormValidate;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    public function profile(){
        $user = auth()->user();

        $data = new ProfileResource($user);

        return success('Success' , $data);
    }

    public function transaction(Request $request){
        $authUser = auth()->user();
        $transactions = Transaction::with('user','source')->orderBy('created_at', 'DESC')->where('user_id', $authUser->id);

        if ($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }
        if ($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
         }

        $transactions = $transactions->paginate(5);

        $data = TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'Success']);

        return $data;
    }

    public function transactionDetail($trx_id){
        $authUser = auth()->user();
        $transaction = Transaction::where('user_id', $authUser->id)->where('trx_id', $trx_id)->firstOrfail();

        $data = new TransactionDetailResource($transaction);

        return $data;
    }

    public function notification(){
        $authUser = auth()->user();
        $notifications = $authUser->notifications()->paginate(5);

        $data = NotificationResource::collection($notifications)->additional(['result' => 1, 'message' => 'Success']);

        return $data;
    }

    public function notificationDetail($id){
        $authUser = auth()->user();
        $notification = $authUser->notifications()->where('id', $id)->firstOrFail();

        $data = new NotificationDetailResource($notification);

        return success('Success' ,$data);
    }

    public function toAccountVerify(Request $request){

        if ($request->phone) {
            $authUser = auth()->user();
            if ($authUser->phone != $request->phone) {
                $user = User::where('phone', $request->phone)->first();
                if ($user) {
                    return success('Success',['name' => $user->name , 'phone' => $user->phone]);
                }
            }
        }

        return fail('Invalid Data' ,null);
    }

    public function transferConfirm(TransferFormValidate $request){

        $authUser = Auth()->user();

        $from_account = $authUser;
        $amount = $request->amount;
        $phone = $request->to_phone;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        if ($hash_value !== $hash_value2) {
            return fail('The given data is invalid.' ,null);
        }

        if ( $amount < 1000 ) {
            return fail('The amount must be at 1000 MMK.' ,null);
        }
        $to_account = User::where('phone' , $phone)->first();
        if (!$to_account) {
            return fail('This account does not exist.' ,null);
        }
        if ($authUser->phone == $phone) {
            return fail('This account is invalid.' ,null);
        }
        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('Something wrong.The given data is invalid.' ,null);
        }
        if ($from_account->wallet->amount < $amount) {
            return fail('The amount is not enough.' ,null);
        }

        return success('Success' , [
            'from_name' => $from_account->name,
            'from_phone' => $phone,

            'to_name' => $to_account->name,
            'to_phone' => $phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value
        ]);
    }

    public function transferComplete(TransferFormValidate $request){

        $authUser = Auth()->user();

        if (!$request->password) {
            return fail('Please fill the password.', null);
        }

        if (!Hash::check($request->password , $authUser->password)) {
            return fail('Password is incorrect.', null);
        }

        $from_account = $authUser;
        $amount = $request->amount;
        $phone = $request->to_phone;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        if ($hash_value !== $hash_value2) {
            return fail('The given data is invalid.' ,null);
        }

        if ( $amount < 1000 ) {
            return fail('The amount must be at 1000 MMK.' ,null);
        }
        $to_account = User::where('phone' , $phone)->first();
        if (!$to_account) {
            return fail('This account does not exist.' ,null);
        }
        if ($authUser->phone == $phone) {
            return fail('This account is invalid.' ,null);
        }
        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('Something wrong.The given data is invalid.' ,null);
        }
        if ($from_account->wallet->amount < $amount) {
            return fail('The amount is not enough.' ,null);
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();


            $ref_no = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction;
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction;
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            // From Noti
            $title = 'E-money Transfered!';
            $message = 'Your wallet transfered '.number_format($amount).' MMK to '.$to_account->name.' ('.$to_account->phone.').';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/'.$from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => $from_account_transaction->trx_id
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received '.number_format($amount).' MMK from '.$from_account->name.' ('.$from_account->phone.').';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/'.$to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => $to_account_transaction->trx_id
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            DB::commit();
            return success('Successfully transfered.' ,[
                'trx_id' => $from_account_transaction->trx_id
            ]);

        } catch (\Exception $error) {
            DB::rollback();
            return fail('Something wrong' . $error->getMessage() ,null);
        }
    }

    public function scanAndPayForm(Request $request){

        $from_account = Auth()->user();

        $to_account = User::where('phone',$request->to_phone)->first();
        if (!$to_account) {
            return fail('The given data is invalid',null);
        }

        return success('Success' , [
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone
        ]);
    }

    public function scanAndPayConfirm(TransferFormValidate $request){

        $authUser = Auth()->user();

        $from_account = $authUser;
        $amount = $request->amount;
        $phone = $request->to_phone;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        if ($hash_value !== $hash_value2) {
            return fail('The given data is invalid.' ,null);
        }

        if ( $amount < 1000 ) {
            return fail('The amount must be at 1000 MMK.' ,null);
        }
        $to_account = User::where('phone' , $phone)->first();
        if (!$to_account) {
            return fail('This account does not exist.' ,null);
        }
        if ($authUser->phone == $phone) {
            return fail('This account is invalid.' ,null);
        }
        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('Something wrong.The given data is invalid..' ,null);
        }
        if ($from_account->wallet->amount < $amount) {
            return fail('The amount is not enough.' ,null);
        }

        return success('Success' , [
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value
        ]);
    }

    public function scanAndPayComplete(TransferFormValidate $request){

        $authUser = Auth()->user();

        if (!$request->password) {
            return fail('Please fill the password.', null);
        }

        if (!Hash::check($request->password , $authUser->password)) {
            return fail('Password is incorrect.', null);
        }

        $from_account = $authUser;
        $amount = $request->amount;
        $phone = $request->to_phone;
        $description = $request->description;
        $hash_value = $request->hash_value;

        $str = $phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'magicpay123!@#');
        if ($hash_value !== $hash_value2) {
            return fail('The given data is invalid.' ,null);
        }

        if ( $amount < 1000 ) {
            return fail('The amount must be at 1000 MMK.' ,null);
        }
        $to_account = User::where('phone' , $phone)->first();
        if (!$to_account) {
            return fail('This account does not exist.' ,null);
        }
        if ($authUser->phone == $phone) {
            return fail('This account is invalid.' ,null);
        }
        if (!$from_account->wallet || !$to_account->wallet) {
            return fail('Something wrong.The given data is invalid..' ,null);
        }
        if ($from_account->wallet->amount < $amount) {
            return fail('The amount is not enough.' ,null);
        }

        DB::beginTransaction();
        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount', $amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount', $amount);
            $to_account_wallet->update();


            $ref_no = UUIDGenerate::refNumber();

            $from_account_transaction = new Transaction;
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction;
            $to_account_transaction->ref_no = $ref_no;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            // From Noti
            $title = 'E-money Transfered!';
            $message = 'Your wallet transfered '.number_format($amount).' MMK to '.$to_account->name.' ('.$to_account->phone.').';
            $sourceable_id = $from_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/'.$from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => $from_account_transaction->trx_id
            ];

            Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            // To Noti
            $title = 'E-money Received!';
            $message = 'Your wallet received '.number_format($amount).' MMK from '.$from_account->name.' ('.$from_account->phone.').';
            $sourceable_id = $to_account_transaction->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/'.$to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction_detail',
                'parameter' => $to_account_transaction->trx_id
            ];

            Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link,$deep_link));

            DB::commit();
            return success('Successfully transfered.' ,[
                'trx_id' => $from_account_transaction->trx_id
            ]);

        } catch (\Exception $error) {
            DB::rollback();
            return fail('Something wrong' . $error->getMessage() ,null);
        }
    }
}
