<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rules\Password;
use App\Events\sendSMS;
use App\Models\Order;
use App\Models\UserPassword;
use App\Models\UsersAddress;

class CustomerAuthController extends Controller
{
    /**
     * Register a new customer.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Try to register a new user
        try {
            // Check for validation errors and return error response if any
            $validation = Validator::make($request->all(), [
                'name' => 'required|regex:/^[A-Za-z\s]+$/',
                'mobile_number' => 'required|regex:/\+91[0-9]{10}/|unique:users',
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
                'fcm_token' => 'required',
        
                
            ], [
                'name.required' => 'Please enter a name.',
                'name.regex' => 'Name should only contain letters and spaces.',
                'mobile_number.required' => 'Please enter mobile number.',
                'mobile_number.regex' => 'The mobile number should start with +91 and have 10 digits.',
                'mobile_number.unique' => 'The mobile number is already in use. Please choose another.',
                'password.required' => 'Please enter a password.',
                'password.*' => 'The password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
                'fcm_token.required' => 'Please send fcm token.',
            ]);
            
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }
 
            // Hash the password and set other request attributes
            $password = Hash::make($request->password);
            $request['password'] = $password ;
            $request['role_id'] = '2';
            $request['name'] = $request->name;
            $request['mobile_number'] = $request->mobile_number;
            $request['remember_token'] = Str::random(10);
            $request['fcm_token'] = $request->fcm_token;
            $referralCode = Str::random(10);
            $request['referral_code'] = $referralCode;

            /*

            $allCustomers = User::where('role_id','2')->get();
            $loginUserData = User::find( $customerId);
            $loginUserReferredCode = $loginUserData->referred_code ?? '';
           
            if(isset($allCustomers) && !empty($allCustomers)){
                foreach($allCustomers as $key => $value){
                    echo "loginUserReferredCode = ";
                    echo "<br>";
                    echo $loginUserReferredCode;
                    echo "<br>";
                    echo "user referral_code = ";
                    echo "<br>";
                    echo $value->referral_code;
                    if($loginUserReferredCode == $value->referral_code){
                        echo "<br>";
                        echo "user id =".$value->id;
                        echo "<br>";
                    }
                }
            }*/

            

            $count = User::where('referral_code', $referralCode)->count();

            if($count > 0){
                return response()->json(['status' => 'error', 'code' => 500, 'message' => 'Referral code already exists']);
            }

            $request['referred_code'] = $request->referred_code;



            // Create a new user
            $user = User::create($request->toArray());

            // Generate a token for the user
            $token = JWTAuth::fromUser($user);

            // update auth token
            $userData = User::where('mobile_number',$request->mobile_number)->first();
            $userData->auth_token =  $token;
            $userData->save();

             // Save password in user's password table

            UserPassword::create([
                "customer_id" => $user->id,
                "password" => $password 

            ]);

            $message = "Hello $request->name,Welcome to brobo.You are registered successfully.";

            // send verification code to user's mobile number
           // event(new SendSMS($request->mobile_number,$message));

              // Find the user by customer_id
              $userData = User::find($user->id);

              // Return a success response with the token
              $response = ['status' => 'success', 'code' => 201, 'message' => 'User registered successfully', 'data' => $userData];
            return response()->json($response);
        } catch (\Exception $e) {
          //  dd($e);
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Authenticate a customer and provide a JWT token.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Try to authenticate the customer
        try {
            // Validate the input data
            $validation = Validator::make($request->all(), [
                'mobile_number' => 'required|regex:/\+91[0-9]{10}/',
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
               /* 'fcm_token' => 'required',*/
            ], [
                'mobile_number.required' => 'Please enter mobile number.',
                'mobile_number.regex' => 'The mobile number should start with +91 and have 10 digits.',
                'mobile_number.unique' => 'The mobile number is already in use. Please choose another.',
                'password.required' => 'Please enter a password.',
                'password.*' => 'The password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
              //  'fcm_token.required' => 'Please send fcm token.',
            ]);
            
          
            
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Get credentials from the request
            $credentials = $request->only('mobile_number', 'password');

            // Authenticate the user
            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['status' => 'error', 'code' => 401, 'message' => 'Invalid credentials']);
            }

            // Check the user's role after successful authentication
            $user = Auth::user();

            // Generate a token for the user
            $token = JWTAuth::fromUser($user);

          


            $user->update(['auth_token' => $token , 'fcm_token' => $request->fcm_token]);

            if ($user->role_id != $request->role_id) {
                // The user has an invalid role
                return response()->json(['status' => 'error', 'code' => 401, 'message' => 'Invalid credentials']);
            }

            // If the user's role is correct, return the token
            return response()->json(['status' => 'success', 'code' => 200, 'data' => $user, 'message' => 'Login successfully']);
        } catch (\Exception $e) {

            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

     /**
     * Send verification code to customer mobile number to reset password.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        try {
            // Get mobile_number from the request
            $mobileNumber = $request->post('mobile_number');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'mobile_number' => 'required|regex:/\+91[0-9]{10}/',
            ], [
                'mobile_number.required' => 'Please enter a mobile number.',
                'mobile_number.regex' => 'The mobile number should start with +91 and have 10 digits.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by mobile_number
            $userData = User::where('mobile_number',$mobileNumber)->first();

            if ($userData) {
                // Update verification code 
                $verificationCode = random_int(1000, 9999);
                $userData->verification_code = $verificationCode;
                $userData->save();
                // send verification code to user's mobile number
                $message = "Hello $userData->name,Your verification code is: $verificationCode.Please enter this code to the reset password .If you didn't request this, please ignore this message.Thank you,Brobo";
              //  event(new SendSMS($mobileNumber,$message));


                return response()->json(['status' => 'success', 'code' => 200, 'data' => $userData, 'message' => 'OTP has been sent to your mobile number to reset password']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

     /**
     * Verify OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(Request $request)
    {
        try {
            // Get customer_id and verification_code from the request
            $customerId       = $request->post('customer_id');
            $verificationCode = $request->post('verification_code');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'verification_code' => 'required',
            ], [
                'customer_id.required' => 'Please enter a customer id.',
                'verification_code.required' => 'Please enter a verification code.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::where(['verification_code' => $verificationCode,'id'=> $customerId])->first();

            if ($userData) {              
                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Code verified succesfully']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'Invalid code']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

     /**
     * Resend OTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function resendOTP(Request $request)
    {
        try {
            // Get customer_id and verification_code from the request
            $customerId       = $request->post('customer_id');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
            ], [
                'customer_id.required' => 'Please enter a customer id.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {              
                // Update verification code 
                $verificationCode = random_int(1000, 9999);
                $userData->verification_code = $verificationCode;
                $userData->save();
                // send verification code to user's mobile number
                $message = "Hello $userData->name,Your verification code is: $verificationCode.Please enter this code to the reset password .If you didn't request this, please ignore this message.Thank you,Brobo";
                //  event(new SendSMS($mobileNumber,$message));
                return response()->json(['status' => 'success', 'code' => 200, 'data' => $userData, 'message' => 'OTP has been sent to your mobile number to reset password']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User not found']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }


     /**
     * Create new password for user.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        // Try to update customer details
        try {
            // Get customer_id from the request
            $customerId = $request->post('customer_id');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'new_password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
                'confirm_password' => 'required|same:new_password',
            ], [
                'customer_id.required' => 'Please enter a customer id.',
                'new_password.required' => 'Please enter a new password.',
                'new_password.*' => 'The new password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
                'confirm_password.required' => 'Please confirm the new password.',
                'confirm_password.same' => 'The confirm password must match the new password.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                $password  = Hash::make($request->new_password); 

                // check last 2 used password 

                $userPasswordData = UserPassword::where("customer_id", $customerId)->orderBy('id', 'desc')->limit(2)->get();

                if(count($userPasswordData) > 0){
                    foreach($userPasswordData as $key => $value){              

                        if (Hash::check($request->new_password, $value->password)) {
                            return response()->json(['status' => 'error', 'code' => 422, 'message' => 'New Password can not be same as last two old password'], 422);
                        }
                    }
                }                

                
                // Update password
                 $userData->password =  $password;             
                 $userData->save();

                // Update password
                $userData->password = $password;               
                $userData->save();

                // Save password in user's password table

                UserPassword::create([
                    "customer_id" => $customerId,
                    "password" => $password 
                ]);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Password updated successfully']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get details of a customer by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function getCustomerDetails(Request $request)
    {
      
        // Try to get customer details
        try {

            // Get customer_id from the token
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = $user->id;

          
            

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                return response()->json(['code' => 200, 'status' => 'success', 'data' => $userData, 'message' => 'Data found successfully']);
            } else {
                return response()->json(['code' => 404, 'status' => 'error', 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update customer details by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function updateCustomerDetails(Request $request)
    {
        // Try to update customer details
        try {

            // Get customer_id from the token
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = $user->id;

            $emailValidation = "";
            $genderValidation = "";
            $nameValidation = "";
            $mobileValidation = "";

            if(!empty($request->post('email'))){
                $emailValidation = 'email|unique:users,email,'.$customerId;
            }

            if(!empty($request->post('gender'))){
                $genderValidation = 'in:Male,Female';
            }

            if(!empty($request->post('name'))){
                $nameValidation = 'regex:/^[A-Za-z\s]+$/';
            }

            if(!empty($request->post('mobile_number'))){
                $mobileValidation = 'regex:/\+91[0-9]{10}/|unique:users,mobile_number,'.$customerId;
            }

            

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'name' => $nameValidation,
                'mobile_number' => $mobileValidation,
                'email' => $emailValidation,
                'gender' => $genderValidation
            ], [
                'name.regex' => 'Name should only contain letters and spaces.',
                'mobile_number.regex' => 'The mobile number should start with +91 and have 10 digits.',
                'mobile_number.unique' => 'The mobile number is already in use. Please choose another.',
                'email.*' => 'The email address is invalid. Please provide a valid email.',
                'gender.*' => 'Invalid gender selection. Please choose a valid gender option.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                // Update user details
                $userData->name = $request->post('name');
                $userData->email = $request->post('email');
                $userData->gender = $request->post('gender');
                $userData->mobile_number = $request->post('mobile_number');

                $userData->save();

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Profile updated successfully']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

     /**
     * Change password of user.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        // Try to update customer details
        try {
            // Get customer_id from the request
            $customerId = $request->post('customer_id');

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'old_password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
                'new_password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
                'confirm_password' => 'required|same:new_password',
            ], [
                'customer_id.required' => 'Please enter a customer id.',
                'old_password.required' => 'Please enter the old password.',
                'old_password.*' => 'The old password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
                'new_password.required' => 'Please enter a new password.',
                'new_password.*' => 'The new password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
                'confirm_password.required' => 'Please confirm the new password.',
                'confirm_password.same' => 'The confirm password must match the new password.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                // Check if the provided current password matches the hashed password in the database
                if (!Hash::check($request->old_password, $userData->password)) {
                    return response()->json(['status' => 'error', 'code' => 422, 'message' => 'Current password is incorrect'], 422);
                }

                $password = Hash::make($request->new_password);

                // check last 2 used password 

                $userPasswordData = UserPassword::where("customer_id", $customerId)->orderBy('id', 'desc')->limit(2)->get();

                if(count($userPasswordData) > 0){
                    foreach($userPasswordData as $key => $value){              

                        if (Hash::check($request->new_password, $value->password)) {
                            return response()->json(['status' => 'error', 'code' => 422, 'message' => 'New Password can not be same as last two old password'], 422);
                        }
                    }
                }                

                
                // Update password
                 $userData->password =  $password;             
                 $userData->save();


                  // Save password in user's password table

                UserPassword::create([
                    "customer_id" => $customerId,
                    "password" => $password 
                ]);

                return response()->json(['status' => 'success', 'code' => 200, 'message' => 'Password updated successfully']);
            } else {
                return response()->json(['status' => 'error', 'code' => 404, 'message' => 'User not found']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }


    /**
     * Verify password of user.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function verifyPassword(Request $request)
    {
        // Try to get customer details
        try {
            // Get customer_id and password from the request
            $customerId = $request->post('customer_id');
           

            // Validate the input data
            $validation = Validator::make($request->all(), [
                'customer_id' => 'required',
                'current_password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->letters()
                        ->numbers()
                        ->symbols()
                ],
            ], [
                'customer_id.required' => 'Please enter a customer id.',
                'current_password.required' => 'Please enter the current password.',
                'current_password.*' => 'The current password must meet the following criteria: at least 8 characters long, contain at least one uppercase and one lowercase letter, at least one letter, at least one number, and at least one special character.',
            ]);
            

            // Check for validation errors and return error response if any
            if ($validation->fails()) {
                return response()->json(['status' => 'error', 'code' => 422, 'message' => $validation->errors()->first()]);
            }

            // Find the user by customer_id
            $userData = User::find($customerId);

            if ($userData) {
                // Check if the provided current password matches the hashed password in the database
                if (!Hash::check($request->current_password, $userData->password)) {
                    return response()->json(['status' => 'error', 'code' => 422, 'message' => 'Password is incorrect'], 422);
                }

                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Password verified successfully']);
            } else {
                return response()->json(['code' => 404, 'status' => 'error', 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a customer account by customer_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount(Request $request)
    {
        // Try to delete a customer account
        try {




            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $customerId = $user->id;

            // Find the user by customer_id
            $user = User::find($customerId);

            if ($user) {
                // Invalidate the user's token
                JWTAuth::invalidate(JWTAuth::getToken());

              

                // Delete the user 
                $user->delete();
                
                 // delete user's booking data
         
         Order::where(['user_id'=>$customerId])->delete();
         
         // delete user's password data 
          UserPassword::where(['customer_id'=>$customerId])->delete();
         
          // delete user's address data 
          
         UsersAddress::where(['customer_id'=>$customerId])->delete();
         
           // Log the user out
                Auth::logout();
                
                

                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Account deleted successfully']);
            } else {
                return response()->json(['code' => 404, 'status' => 'error', 'message' => 'User does not exist']);
            }
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Logout a customer and invalidate the JWT token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        // Try to log out the customer and invalidate the token
        try {
            // Invalidate the user's token
            JWTAuth::invalidate(JWTAuth::getToken());

            // Log the user out and delete the account
            Auth::logout();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Logout successfully',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions, if any
            return response()->json(['status' => 'error', 'code' => 500, 'message' => $e->getMessage()]);
        }
    }
}

