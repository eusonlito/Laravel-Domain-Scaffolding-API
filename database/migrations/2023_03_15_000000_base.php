<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\SharedApp\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        $this->tables();
        $this->keys();
        $this->upFinish();
    }

    /**
     * @return void
     */
    protected function tables(): void
    {
        Schema::create('configuration', function (Blueprint $table) {
            $table->id();

            $table->string('key')->unique();
            $table->string('value')->default('');
            $table->string('description')->default('');

            $this->timestamps($table);
        });

        Schema::create('ip_lock', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->index();

            $table->dateTimeTz('end_at')->nullable();

            $this->timestamps($table);
        });

        Schema::create('language', function (Blueprint $table) {
            $table->id();

            $this->uuid($table);

            $table->string('name');
            $table->string('code')->unique();
            $table->string('locale')->unique();

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(0);

            $this->timestamps($table);
        });

        Schema::create('queue_fail', function (Blueprint $table) {
            $table->id();

            $table->text('connection');
            $table->text('queue');

            $table->longText('payload');
            $table->longText('exception');

            $table->dateTime('failed_at')->useCurrent();

            $this->timestamps($table);
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id();

            $this->uuid($table);

            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->default('');
            $table->string('timezone');

            $table->jsonb('preferences')->nullable();

            $table->dateTimeTz('confirmed_at')->nullable();

            $table->boolean('enabled')->default(0);

            $this->timestamps($table);

            $table->unsignedBigInteger('language_id');
        });

        Schema::create('user_code', function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();
            $table->string('code')->index();
            $table->string('ip');

            $table->dateTimeTz('finished_at')->nullable();
            $table->dateTimeTz('canceled_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user_fail', function (Blueprint $table) {
            $table->id();

            $table->string('type')->index();
            $table->string('text')->nullable();
            $table->string('ip')->index();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('user_session', function (Blueprint $table) {
            $table->id();

            $table->string('auth')->index();
            $table->string('ip')->index();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
        });

        Schema::create('user_token', function (Blueprint $table) {
            $table->id();

            $table->string('hash')->unique();
            $table->string('device')->nullable();

            $table->dateTimeTz('expired_at')->nullable();

            $this->timestamps($table);

            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * @return void
     */
    protected function keys(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'language');
        });

        Schema::table('user_code', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('user_fail', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'user');
        });

        Schema::table('user_session', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('user_token', function (Blueprint $table) {
            $table->index(['user_id', 'hash']);

            $this->foreignOnDeleteCascade($table, 'user');
        });
    }
};
