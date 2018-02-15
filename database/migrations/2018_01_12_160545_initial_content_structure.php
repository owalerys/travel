<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialContentStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iata_code', 2)->nullable();
            $table->string('icao_code', 3)->nullable();
            $table->string('name', 255);
            $table->string('country_code', 2)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index('iata_code');
            $table->index('icao_code');
            $table->index('country_code');
            $table->index('active');
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->nullableMorphs('topic');
            $table->string('category_slug');
            $table->string('schema_version');
            $table->string('display_title', 255)->nullable();
            $table->text('display_description')->nullable();
            $table->unsignedInteger('latest_version_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('live')->default(false);
            $table->timestamps();

            $table->index('category_slug');
            $table->index('schema_version');
            $table->index('live');
            $table->index('active');
            $table->index('latest_version_id');
        });

        Schema::create('article_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 2000)->nullable();
            $table->string('status', 32)->default('new');
            $table->json('content')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->string('schema_version');
            $table->string('category_slug');
            $table->unsignedInteger('author_id')->nullable();
            $table->timestamps();

            $table->index('category_slug');
            $table->index('schema_version');
            $table->index('article_id');
            $table->index('status');
            $table->index('author_id');
            $table->index('parent_id');
        });

        Schema::create('article_version_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_version_id');
            $table->unsignedInteger('author_id');
            $table->unsignedInteger('user_id');
            $table->string('new_status', 32)->nullable();
            $table->string('intent', 32);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->index('article_version_id');
            $table->index('author_id');
            $table->index('user_id');
            $table->index('new_status');
            $table->index('intent');
        });

        /*Schema::create('article_version_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('version_id');
            $table->uuid('field_uuid');
            $table->string('title', 255)->nullable();
            $table->string('sub_heading', 255)->nullable();
            $table->string('slug', 255);
            $table->unsignedSmallInteger('order');
            $table->text('content');
            $table->text('additional_info')->nullable();
            $table->unsignedInteger('author_id');
            $table->timestamps();

            $table->index('version_id');
            $table->index('field_uuid');
        });*/

        /*Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('drive', 255);
            $table->string('drive_path', 256);
            $table->uuid('drive_uuid');
            $table->string('display_path', 256);
            $table->string('display_filename', 255);
            $table->string('mime_type', 255);
            $table->unsignedInteger('uploader_id');
            $table->timestamps();

            $table->index('drive');
            $table->index('drive_path');
            $table->index('drive_uuid');
            $table->index('display_path');
            $table->index('display_filename');
            $table->index('uploader_id');
        });*/

        /*Schema::create('article_version_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('upload_id');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order');
            $table->unsignedInteger('author_id');
            $table->timestamps();

            $table->index('upload_id');
            $table->index('author_id');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('airlines');
        Schema::drop('articles');
        Schema::drop('article_versions');
        /*Schema::drop('article_version_fields');*/
        /*Schema::drop('uploads');
        Schema::drop('article_version_files');*/
    }
}
