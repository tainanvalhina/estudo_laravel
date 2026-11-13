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
        Schema::table('produtos', function (Blueprint $table) {
            //foreignId: cria a coluna categoria_id.
            $table->foreignId('categoria_id')
            //nullable: permite que o produto não tenha uma categoria.
                ->nullable()
                //constrained: cria a chave estrangeira que aponta para categorias.id.
                ->constrained('categorias')
                //nullOnDelete: se a categoria for excluída, o produto continua existindo, mas seu categoria_id fica nulo.
                ->nullOnDelete();
        });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Remove a chave estrangeira e a coluna categoria_id.
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categoria_id');
        });
     
    }
};
