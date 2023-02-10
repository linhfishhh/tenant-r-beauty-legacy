<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class CustomAuthenticationException extends Exception
{
    protected $login_route;
    protected $guards;
    /**
     * @param \Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request){
        return $this->unauthenticated($request);
    }

    /**
     * @param \Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request)
    {
        if(!\Route::has($this->login_route)){
            throw new NotFoundHttpException();
        }
        return $request->expectsJson()
            ? response()->json(['message' => $this->getMessage()], 401)
            : redirect()->guest(route($this->login_route));
    }

    public function __construct(string $message = 'Unauthenticated.', $login_route, array $guards = [])
    {

        parent::__construct($message);
        $this->login_route = $login_route;
        $this->guards = $guards;
    }

    public function guards()
    {
        return $this->guards;
    }

}
