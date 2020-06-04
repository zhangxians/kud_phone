<?php

namespace App\Exceptions;


class DataNotException extends ProgramException
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Report the exception.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function render($request)
    {
        // 字段验证异常
        $message = $this->getMessage() ?: '暂无数据';
        $code = $this->getCode() ?: 200;
        $redirect = $this->getRedirect() ?: '/';
        return $request->ajax() || $request->wantsJson() ?
            json_fail($message) :
            response(view('errors.404', compact('code', 'message', 'redirect')), $code);
    }
}
