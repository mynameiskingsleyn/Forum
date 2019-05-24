<?php

namespace Forum\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Forum\Exceptions\ThrottleException;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new \Forum\Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body'=>'required|spamfree|min:10'
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return[
          'body.reqired'=>'Body is required',
          'body.spamfree'=>' your input has spam please fix!!',
          'body.min:10'=>'You must have more than 9 characters on the body'
      ];
    }

    // public function persist($thread)
    // {
    //     return $thread->addReply([
    //         'body' => request('body'),
    //         'user_id' => auth()->id()
    //     ])->load('owner');
    // }

    protected function failedAuthorization()
    {
        throw new ThrottleException();
    }

    // protected function failedValidation()
    // {
    //     throw new SpamException();
    // }
}
