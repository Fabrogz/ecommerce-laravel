<!-- Ejemplo en resources/views/products/create.blade.php -->
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="name" placeholder="Nombre" required>
    <textarea name="description" placeholder="Descripción"></textarea>
    <input type="number" name="price" step="0.01" placeholder="Precio" required>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <input type="file" name="image">
    <button type="submit">Guardar Producto</button>
</form>