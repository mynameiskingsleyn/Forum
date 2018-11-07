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
            'body'=>'required|spamfree'
        ];
    }

    public function persist($thread)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ])->load('owner');
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently');
    }
}
