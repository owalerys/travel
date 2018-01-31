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
            $table->unsignedInteger('country_id')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['iata_code', 'icao_code', 'country_id', 'active']);
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('airline_id');
            $table->string('category_slug');
            $table->string('display_title', 255)->nullable();
            $table->text('display_description')->nullable();
            $table->unsignedInteger('latest_version_id')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('live')->default(false);
            $table->timestamps();

            $table->index(['category_slug', 'airline_id', 'live', 'active', 'latest_version_id']);
        });

        Schema::create('article_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('url', 2000);
            $table->string('status', 32)->default('new');
            $table->unsignedInteger('version')->default(1);
            $table->unsignedInteger('author_id');
            $table->timestamps();

            $table->index(['article_id', 'status', 'author_id', 'parent_id']);
        });

        Schema::create('article_version_fields', function (Blueprint $table) {
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

            $table->index(['version_id', 'field_uuid']);
        });

        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('drive', 255);
            $table->string('drive_path', 1000);
            $table->uuid('drive_uuid');
            $table->string('display_path', 1000);
            $table->string('display_filename', 255);
            $table->string('mime_type', 255);
            $table->unsignedInteger('uploader_id');
            $table->timestamps();

            $table->index(['drive', 'drive_path', 'drive_uuid', 'display_path', 'display_filename', 'uploader_id']);
        });

        Schema::create('article_version_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('upload_id');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('order');
            $table->unsignedInteger('author_id');
            $table->timestamps();

            $table->index(['upload_id', 'author_id']);
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso_code', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airlines');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_versions');
        Schema::dropIfExists('article_version_fields');
        Schema::dropIfExists('uploads');
        Schema::dropIfExists('article_version_files');
        Schema::dropIfExists('countries');
    }
}
