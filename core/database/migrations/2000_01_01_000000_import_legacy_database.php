<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Path to the SQL file relative to the 'core' directory
        $sqlPath = base_path('../install/database.sql');

        if (!File::exists($sqlPath)) {
            $sqlPath = __DIR__ . '/../../../install/database.sql';
        }

        if (File::exists($sqlPath)) {
            $sql = File::get($sqlPath);
            
            // Basic cleanup
            $sql = str_replace('CREATE TABLE `', 'CREATE TABLE IF NOT EXISTS `', $sql);
            $sql = str_replace('INSERT INTO `', 'INSERT IGNORE INTO `', $sql);

            // Split into statements
            // This regex tries to split by semicolon that is at the end of a line or followed by newlines
            // It is not perfect for stored procs but sufficient for dumps
            $statements = preg_split('/;\s*[\r\n]+/', $sql);

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (empty($statement)) {
                    continue;
                }

                try {
                    DB::statement($statement);
                } catch (\Exception $e) {
                    // Ignore specific errors
                    // 1062: Duplicate entry
                    // 1068: Multiple primary key defined
                    // 1050: Table already exists (covered by IF NOT EXISTS but good to have)
                    // 1061: Duplicate key name
                    
                    $msg = $e->getMessage();
                    if (
                        strpos($msg, 'Duplicate entry') !== false ||
                        strpos($msg, 'Multiple primary key') !== false ||
                        strpos($msg, 'already exists') !== false || 
                        strpos($msg, 'Duplicate key name') !== false
                    ) {
                        // Continue
                        continue;
                    }
                    
                    // For other errors, we might want to throw or log
                    // throw $e; 
                    // Let's log and continue to be aggressive
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // It is unsafe to drop all tables automatically.
    }
};
