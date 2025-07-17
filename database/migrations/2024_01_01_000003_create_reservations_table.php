
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("reservations", function (Blueprint $table) {
            $table->id();
            $table->foreignId("customer_id")->constrained()->onDelete("cascade");
            $table->foreignId("table_id")->constrained()->onDelete("cascade");
            $table->dateTime("date_time");
            $table->text("note")->nullable();
            $table->timestamps();
            
            // Add index for efficient querying of reservations by date and table
            $table->index(["table_id", "date_time"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("reservations");
    }
};

