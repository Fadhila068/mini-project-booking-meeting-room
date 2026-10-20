<?php

namespace Database\Seeders;

use App\Models\{User,Facility,MeetingRoom,Setting,Booking};
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin=User::firstOrCreate(['email'=>'admin@ruangkita.test'], ['name'=>'Administrator','password'=>Hash::make('password'),'role'=>'admin','department'=>'General Affairs']);
        $employee=User::firstOrCreate(['email'=>'budi@ruangkita.test'], ['name'=>'Budi Santoso','password'=>Hash::make('password'),'role'=>'employee','department'=>'Teknologi Informasi']);
        $facilities=collect(['Projector','TV Display','Whiteboard','Video Conference','AC','Wi-Fi','Speaker'])->mapWithKeys(fn($n)=>[$n=>Facility::firstOrCreate(['name'=>$n])]);
        $alpha=MeetingRoom::firstOrCreate(['code'=>'MR-A'],['name'=>'Ruang Meeting A','location'=>'Lantai 1','capacity'=>6,'description'=>'Ruang meeting untuk diskusi tim kecil.','opens_at'=>'08:00','closes_at'=>'18:00','is_active'=>true]);$alpha->facilities()->syncWithoutDetaching([$facilities['Projector']->id,$facilities['Whiteboard']->id,$facilities['AC']->id,$facilities['Wi-Fi']->id]);
        foreach ([['MR-B','Ruang Meeting B',10],['MR-C','Ruang Meeting C',15],['MR-BESAR','Ruang Meeting Besar',20]] as [$code,$name,$capacity]) { $room=MeetingRoom::firstOrCreate(['code'=>$code],['name'=>$name,'location'=>'Lantai 2','capacity'=>$capacity,'description'=>'Ruang meeting yang nyaman untuk kebutuhan kolaborasi.','opens_at'=>'08:00','closes_at'=>'18:00','is_active'=>true]);$room->facilities()->syncWithoutDetaching([$facilities['TV Display']->id,$facilities['AC']->id,$facilities['Wi-Fi']->id]); }
        foreach(['min_booking_minutes'=>'30','max_booking_minutes'=>'480'] as $key=>$value) Setting::firstOrCreate(['key'=>$key],['value'=>$value]);
        Booking::firstOrCreate(['booking_code'=>'BK-DEMO-00001'],['user_id'=>$employee->id,'meeting_room_id'=>$alpha->id,'title'=>'Weekly Engineering','start_at'=>now()->addDay()->setTime(10,0),'end_at'=>now()->addDay()->setTime(11,0),'participant_count'=>6,'status'=>'approved','approved_by'=>$admin->id,'approved_at'=>now()]);
    }
}
