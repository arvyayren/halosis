<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //jwt auth
    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    public static function getUser(){
        $response = array('data' => [], 'error' => 1, 'message' => '');

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                $response['message'] = 'user_not_found';
                return $response;
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $response['message'] = 'token_expired';
            $response['data'] = $e->getStatusCode();
            return $response;

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $response['message'] = 'token_invalid';
            $response['data'] = $e->getStatusCode();
            return $response;

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            $response['message'] = 'token_absent';
            $response['data'] = $e->getStatusCode();
            return $response;

        }
        
        $response['data'] = compact('user');
        $response['error'] = 0;

        return $response;
    }
}
