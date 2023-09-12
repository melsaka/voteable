    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    protected $tableName;
    
    public function __construct()
    {
        $this->tableName = config('voteable.table', 'votes');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->defaultTableSchema();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropDefaultTable();
    }

    public function createTableSchema($tableName)
    {
        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->morphs('voteable');
            $table->morphs('voter');
            
            $table->tinyInteger('vote');

            $table->timestamps();
        });
    }

    public function defaultTableSchema()
    {
        $this->createTableSchema($this->tableName);
    }

    public function dropDefaultTable()
    {
        Schema::dropIfExists($this->tableName);
    }
}
