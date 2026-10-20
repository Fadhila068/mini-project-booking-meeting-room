<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void {
 Schema::create('meeting_rooms', function(Blueprint $t) { $t->id(); $t->string('name'); $t->string('code')->unique(); $t->string('location'); $t->unsignedInteger('capacity'); $t->text('description')->nullable(); $t->string('image_path')->nullable(); $t->time('opens_at')->nullable(); $t->time('closes_at')->nullable(); $t->boolean('is_active')->default(true)->index(); $t->timestamps(); $t->softDeletes(); });
 Schema::create('facilities', function(Blueprint $t) { $t->id(); $t->string('name')->unique(); $t->timestamps(); });
 Schema::create('facility_meeting_room', function(Blueprint $t) { $t->foreignId('meeting_room_id')->constrained()->cascadeOnDelete(); $t->foreignId('facility_id')->constrained()->cascadeOnDelete(); $t->primary(['meeting_room_id','facility_id']); });
 } public function down(): void { Schema::dropIfExists('facility_meeting_room'); Schema::dropIfExists('facilities'); Schema::dropIfExists('meeting_rooms'); } };
