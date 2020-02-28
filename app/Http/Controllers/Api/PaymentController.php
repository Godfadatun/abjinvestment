<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\payment;
use App\Invoices;
use Auth;
use App;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $payment = payment::with('User')->get();

        // return response([
        //     'message'=> 'success',
        //     'status' => 'success',
        //     'data'=> $payment,
        //     ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    function getKey($seckey)
    {
        $hashedkey = md5($seckey);
        $hashedkeylast12 = substr($hashedkey, -12);        $seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
        $seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);        $encryptionkey = $seckeyadjustedfirst12 . $hashedkeylast12;
        return $encryptionkey;
    }

    function encrypt3Des($data, $key)
    {
        $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
        return base64_encode($encData);
    }

    public function store(Request $request)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $data = array(
            'PBFPubKey' => env('RAVE_PUBLIC_KEY'),
            'cardno' => $request->cardno,
            'currency' => $request->currency,
            'country' => $request->country,
            'cvv' => $request->cvv,
            'amount' => $request->amount,
            'expiryyear' => $request->expiryyear,
            'expirymonth' => $request->expirymonth,
            'email' => Auth::user()->email,
            'phonenumber' => Auth::user()->email,
            "firstname" => Auth::user()->name,
            "lastname" => '',
            'IP' => $request->IP,
            'txRef' => '5M-' . Auth::user()->id . $request->txRef,
            'meta' => $request->meta,
            // 'subaccounts' => '',
            'suggested_auth' => 'pin',
            'pin' => $request->pin,
            'device_fingerprint' => $request->device_fingerprint
        );
        $SecKey = env('RAVE_SECRET');
        $key = $this->getKey($SecKey);
        $dataReq = json_encode($data);
        $post_enc = $this->encrypt3Des($dataReq, $key);
        $postdata = array(
            'PBFPubKey' => env('RAVE_PUBLIC_KEY'),
            'client' => $post_enc,
            'alg' => '3DES-24'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('RAVE_BASE_URL') . '/flwv3-pug/getpaidx/api/charge');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        $headers = array('Content-Type: application/json');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $request = curl_exec($ch);

        if ($request) {
            return json_decode($request, true);
        } else {
            if (curl_error($ch)) {
                return curl_error($ch);
            }
        }        curl_close($ch);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validate_payment(Request $request)
    {        //validate Transaction with OTP
        error_reporting(E_ALL);
        ini_set('display_errors', 1);        $postdata = array(
            'PBFPubKey' => env('RAVE_PUBLIC_KEY'),
            "transaction_reference" => $request->flwRef,
            "otp" => $request->otp,
        );        $ch = curl_init();        curl_setopt($ch, CURLOPT_URL, env('RAVE_BASE_URL') . "/flwv3-pug/getpaidx/api/validatecharge");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);        $headers = array('Content-Type: application/json');        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        $pay_request = curl_exec($ch);        if ($pay_request) {
            // updated invoice with transaction refrence from rave flutterwave
            Invoices::where('invoice_number', '#' . $request->invoice_number)->update([
                'flwRef' =>  $request->flwRef,
            ]);
            return json_decode($pay_request, true);
        } else {
            if (curl_error($ch)) {
                return curl_error($ch);
            }
        }        curl_close($ch);
    }

    public function verify_payment(Request $request)
    {
        //verify payment
        error_reporting(E_ALL);
        ini_set('display_errors', 1);        $postdata = array(
            'SECKEY' => env('RAVE_SECRET'),
            "txref" => $request->txRef,
        );        $ch = curl_init();        curl_setopt($ch, CURLOPT_URL, env('RAVE_BASE_URL') . "/flwv3-pug/getpaidx/api/v2/verify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);        $headers = array('Content-Type: application/json');        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);        $pay_request = curl_exec($ch);        if ($pay_request) {
            // update Invoice to paid
            Invoices::where('invoice_number', '#' . $request->invoice_number)->update([
                'status' => 1
            ]);
            // $invoice = Invoices::where('invoice_number', '#'.$request->invoice_number)->first();
            // $user = Auth::user();
            // Mail::to($user)->send(new PaymentReceipt($user, $invoice));
            return json_decode($pay_request, true);
        } else {
            if (curl_error($ch)) {
                return curl_error($ch);
            }
        }        curl_close($ch);
    }


    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
