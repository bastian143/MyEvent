<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {

            $table->id();

            $table->foreignId('category_id')
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('creator_id')
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('title');

            $table->string('slug')
                  ->unique();

            $table->longText('description');

            $table->string('poster')
                  ->nullable();

            $table->string('location')
                  ->nullable();

            $table->enum('event_type', [
                'online',
                'offline',
                'hybrid'
            ]);

            $table->enum('registration_type', [
                'individual',
                'team'
            ])->default('individual');

            $table->date('registration_deadline')
                  ->nullable();

            $table->dateTime('start_date');

            $table->dateTime('end_date');

            $table->integer('quota')
                  ->default(0);

            $table->decimal('price', 10, 2)
                  ->default(0);

            $table->string('contact_person')
                  ->nullable();

            $table->string('contact_phone')
                  ->nullable();

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
                'ongoing',
                'finished',
                'archived'
            ])->default('pending');

            $table->text('rejection_reason')
                  ->nullable();

            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamp('approved_at')
                  ->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
