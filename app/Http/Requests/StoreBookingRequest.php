<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreBookingRequest extends FormRequest { public function authorize():bool{return true;} public function rules():array{return ['meeting_room_id'=>['required','exists:meeting_rooms,id'],'title'=>['required','string','max:120'],'description'=>['nullable','string','max:2000'],'start_at'=>['required','date','after_or_equal:now'],'end_at'=>['required','date','after:start_at'],'participant_count'=>['required','integer','min:1']];} }
