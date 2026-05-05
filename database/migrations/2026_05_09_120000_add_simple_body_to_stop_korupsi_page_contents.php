<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('stop_korupsi_page_contents')) {
            return;
        }

        if (! Schema::hasColumn('stop_korupsi_page_contents', 'simple_body_id')) {
            Schema::table('stop_korupsi_page_contents', function (Blueprint $table): void {
                $table->longText('simple_body_id')->nullable()->after('link_span_lapor_url');
            });
        }

        if (! Schema::hasColumn('stop_korupsi_page_contents', 'simple_body_en')) {
            Schema::table('stop_korupsi_page_contents', function (Blueprint $table): void {
                $table->longText('simple_body_en')->nullable()->after('simple_body_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('stop_korupsi_page_contents')) {
            return;
        }

        $drop = [];
        if (Schema::hasColumn('stop_korupsi_page_contents', 'simple_body_en')) {
            $drop[] = 'simple_body_en';
        }
        if (Schema::hasColumn('stop_korupsi_page_contents', 'simple_body_id')) {
            $drop[] = 'simple_body_id';
        }
        if ($drop !== []) {
            Schema::table('stop_korupsi_page_contents', function (Blueprint $table) use ($drop): void {
                $table->dropColumn($drop);
            });
        }
    }
};
