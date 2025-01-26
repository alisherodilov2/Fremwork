<?php

namespace App\Console\MigrationsCommad;
use CommandInterface;

class MakeMigrationCommand implements CommandInterface {
    protected $name;
    protected $directory = 'migrations/';

    public function __construct($name) {
        $this->name = $name;
    }

    public function handle() {
        $filename = $this->directory . date('Y_m_d_His') . '_' . $this->name . '.php';
        $content = "<?php\n\nuse App\Database\Migration;\n\nclass {$this->name} extends Migration {\n    public function up() {\n        // Write your migration logic here\n    }\n\n    public function down() {\n        // Write your rollback logic here\n    }\n}\n";
        file_put_contents($filename, $content);
        echo "Migration {$this->name} created at {$filename}\n";
    }
}
