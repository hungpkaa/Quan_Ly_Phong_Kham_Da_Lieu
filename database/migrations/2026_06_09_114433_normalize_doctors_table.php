<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NormalizeDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            // We can't strictly enforce foreign key immediately if some doctors don't have matching users
        });

        // Migrate existing doctors to users
        $doctors = \Illuminate\Support\Facades\DB::table('doctors')->get();
        foreach ($doctors as $doc) {
            $user = \Illuminate\Support\Facades\DB::table('users')->where('email', $doc->email)->first();
            if (!$user) {
                $userId = \Illuminate\Support\Facades\DB::table('users')->insertGetId([
                    'name' => $doc->name,
                    'email' => $doc->email,
                    'password' => $doc->password,
                    'phone' => $doc->phone ?? '',
                    'role' => 'admindoctor', // Or whatever doctor role is
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \Illuminate\Support\Facades\DB::table('doctors')->where('id', $doc->id)->update(['user_id' => $userId]);
            } else {
                \Illuminate\Support\Facades\DB::table('doctors')->where('id', $doc->id)->update(['user_id' => $user->id]);
            }
        }

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'password', 'phone']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
        });

        // Restore data
        $doctors = \Illuminate\Support\Facades\DB::table('doctors')->get();
        foreach ($doctors as $doc) {
            if ($doc->user_id) {
                $user = \Illuminate\Support\Facades\DB::table('users')->where('id', $doc->user_id)->first();
                if ($user) {
                    \Illuminate\Support\Facades\DB::table('doctors')->where('id', $doc->id)->update([
                        'name' => $user->name,
                        'email' => $user->email,
                        'password' => $user->password,
                        'phone' => $user->phone,
                    ]);
                }
            }
        }

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
