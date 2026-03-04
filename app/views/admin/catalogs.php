<!-- Catalogs management -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Sidebar of categories -->
    <div class="bg-white rounded-lg shadow-sm border p-4 md:col-span-1 h-fit">
        <h3 class="font-bold text-gray-800 mb-4 px-2">Categorías</h3>
        <ul class="space-y-1 text-sm">
            <li>
                <button class="w-full text-left px-3 py-2 rounded-md bg-primary-50 text-primary-600 font-medium">Nacionalidades</button>
            </li>
            <li>
                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-50 text-gray-600">Tipos de Estudiante</button>
            </li>
            <li>
                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-50 text-gray-600">Carreras</button>
            </li>
            <li>
                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-50 text-gray-600">Sectores de Trabajo</button>
            </li>
            <li>
                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-50 text-gray-600">Tipos de Vivienda</button>
            </li>
            <li class="pt-2 mt-2 border-t">
                <button class="w-full text-left px-3 py-2 rounded-md text-blue-500 hover:text-blue-700 text-xs font-semibold">
                    <i class="fas fa-plus mr-1"></i> Nueva Categoría
                </button>
            </li>
        </ul>
    </div>

    <!-- Content area -->
    <div class="bg-white rounded-lg shadow-sm border p-6 md:col-span-3">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Nacionalidades</h3>
                <p class="text-sm text-gray-500">Gestiona las opciones disponibles para este campo.</p>
            </div>
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
                <i class="fas fa-plus mr-2"></i> Añadir Opción
            </button>
        </div>

        <!-- Options Table -->
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-y">
                    <th class="py-3 px-4 font-semibold text-sm w-16">ID/Valor</th>
                    <th class="py-3 px-4 font-semibold text-sm">Nombre a mostrar</th>
                    <th class="py-3 px-4 font-semibold text-sm w-24 text-center">Estado</th>
                    <th class="py-3 px-4 font-semibold text-sm w-24 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y">
                <!-- Data mock -->
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">1</td>
                    <td class="py-3 px-4 font-medium text-gray-800">Venezolano(a)</td>
                    <td class="py-3 px-4 text-center">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-blue-500 hover:text-blue-700 mx-1"><i class="fas fa-edit"></i></button>
                        <button class="text-red-500 hover:text-red-700 mx-1"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">2</td>
                    <td class="py-3 px-4 font-medium text-gray-800">Extranjero(a)</td>
                    <td class="py-3 px-4 text-center">
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Activo</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-blue-500 hover:text-blue-700 mx-1"><i class="fas fa-edit"></i></button>
                        <button class="text-red-500 hover:text-red-700 mx-1"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
