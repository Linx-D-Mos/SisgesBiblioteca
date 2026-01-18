📘 LEARNING LOG - Proyecto 1: Gestión de Biblioteca
Fecha: 17 Enero 2026 Estado: Configuración de BD y Seeding completado.

1. Diseño de Base de Datos (Schema)
Aprendí que el diseño inicial es crítico. Un error aquí (como una mala relación) causa deuda técnica inmediata.

Relación Muchos a Muchos (N:M):

Caso: Libros <-> Autores.

Solución: Se requiere una Tabla Pivote.

Convención Laravel: Orden alfabético de los modelos en singular (author_book).

Migración: Usar foreignId()->constrained()->onDelete('cascade') para evitar registros huérfanos.

Integridad de Datos:

Usar unsignedInteger para stocks (no existen stocks negativos).

Usar timestamp nullable (returned_at) en lugar de un campo de estado string (status). Si es null, está prestado; si tiene fecha, se devolvió.

2. Eloquent ORM & Modelos
Naming Conventions:

Si la relación devuelve uno: singular (ej. book()).

Si la relación devuelve colección: plural (ej. books(), loans()).

Configuración de Relaciones:

belongsToMany: Usado en Book y Author (gracias a la tabla pivote).

hasMany / belongsTo: Usado para Préstamos.

3. Factories & Faker
Errores corregidos al generar datos falsos:

Magnitud: randomNumber(20) genera 20 dígitos. Para rangos (0-20) se usa numberBetween(0, 20).

Tipos de Datos: No mezclar objetos DateTime en campos definidos como integer (años). Usar $this->faker->year().

Nombres: Usar firstName() en lugar de name() para evitar prefijos como "Mr." o "Dr.".

4. Seeding Avanzado (Lógica de Negocio)
Aprendí a no depender siempre de la "magia" de los factories, sino a escribir lógica PHP en el DatabaseSeeder para casos complejos.

Seed de Relación N:M:

PHP
// Crear libros y adjuntar autores aleatorios al vuelo
$books = Book::factory(15)->create()->each(function ($book) use ($authors) {
    $book->authors()->attach($authors->random(rand(1, 3)));
});
Seed Condicional (Préstamos):

Iteramos sobre estudiantes creados.

Usamos rand() para decidir si crear préstamos o no.

Controlamos manualmente returned_at para simular libros pendientes vs. devueltos.

5. Herramientas
Git: La interfaz gráfica de VS Code muestra el Staging Area, no el historial. Para ver el historial real: git log --oneline o extensión "Git Graph".

Comando de Reinicio: php artisan migrate:fresh --seed (Borra todo, migra y siembra).
