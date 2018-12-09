<?php

namespace Jomos\SenangPay;

class SenangPay {

      public $merchantId;
      public $secretKey;

      protected $detail;
      protected $amount;
      protected $orderId;
      protected $name;
      protected $email;
      protected $phone;

      /**-------------------------------------------------------------------------------------------------------------------/    
       *    @description    function description
       *    @author        Idham Hafidz JOMos    idham@jomos.com.my
       *    @param
       *    @return 
       */
      public function __construct()
      {
          $this->merchantId = config('senang-pay.merchant_id');
          $this->secretKey = config('senang-pay.secret_key');
      }

      /**-------------------------------------------------------------------------------------------------------------------/    
       *    @description function description
       *    @author      Idham Hafidz JOMos    idham@jomos.com.my
       *    @param       $request "$request object from controller"
       *    @return 
       */
      public function setSendPaymentDetails( $request, $detail, $orderId, $amount )
      {
          $this->detail = $detail;
          $this->amount = $amount;
          $this->orderId = $orderId;
          if($request->full_name1)
          {
              $this->name = $request->full_name1;
              $this->email = $request->email1;
              $this->phone = $request->contact_number1;
          } else {
              $this->name = $request->full_name;
              $this->email = $request->email;
              $this->phone = $request->contact_number;
          }

          return $this;
      }


       /*--------------------------------------------------------------------------------------------------------------------/	
        *
        *	@description  This will generate hash
        *	@author		  Idham Hafidz JOMos	idham@jomos.com.my
        */
      public function generateHash()
      {
		    return md5( $this->secretKey.$this->detail.$this->amount.$this->orderId );
	  }

       /*--------------------------------------------------------------------------------------------------------------------/	
        *
        *	@description	This will generate the HTTP query
        *	@author		Idham Hafidz JOMos	idham@jomos.com.my
        */
       public function generateHttpQuery()
       {
            $httpQuery = http_build_query([
                'detail' => $this->detail,
                'amount' => $this->amount,
                'hash' => $this->generateHash(),
                'order_id' => $this->orderId,
                'phone'=> $this->phone,
                'email' => $this->email,
                'name' => $this->name
            ]);

		    return $httpQuery;
       }

       /*--------------------------------------------------------------------------------------------------------------------/	
       *    @description  This will send details of payment to SenangPay
       *    @author       Idham Hafidz JOMos    idham@jomos.com.my
       *    @return 
       */
      public function processPayment()
      {
        return 'https://app.senangpay.my/payment/'.$this->merchantId.'?'.$this->generateHttpQuery();
      }


       /*--------------------------------------------------------------------------------------------------------------------/	
        *
        *	@description  This will generate the return Hash to match with incoming transaction
        *	@author		  Idham Hafidz JOMos	idham@jomos.com.my
        *	@param        $request  "request object from controller"
        */
      protected function generateReturnHash( $request )
      {
		$returnHash = md5($this->secretKey.'?status_id='.$request->status_id.'&order_id='.$request->order_id.'&transaction_id='.$request->transaction_id.'&message='.$request->message.'&hash=[HASH]');
		return $returnHash;
	 }

       /*--------------------------------------------------------------------------------------------------------------------/	
        *
        *	@description  This will check if the parametered hash is correct and not mess by MITM (Men In The Middle)
        *	@author		  Idham Hafidz JOMos	idham@jomos.com.my
        *	@param        $request  "request object from controller"
        */
       public function checkIfReturnHashCorrect( $request )
       {
	       $parameterHash = $request->hash;
	       if($this->generateReturnHash( $request) == $parameterHash )
	       {
		     return true;
	       } else {
		     return false;
	       }
       }

}
