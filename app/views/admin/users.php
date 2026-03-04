<!-- Users management -->
<div class="bg-white rounded-lg shadow-sm border p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Lista de Usuarios</h3>
        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
            <i class="fas fa-plus mr-2"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Search/Filter -->
    <div class="mb-4 flex gap-4">
        <input type="text" placeholder="Buscar por nombre o correo..." class="border border-gray-300 rounded-md p-2 w-full md:w-1/3 focus:ring-primary-500 focus:border-primary-500 outline-none">
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-y">
                    <th class="py-3 px-4 font-semibold text-sm">Nombre</th>
                    <th class="py-3 px-4 font-semibold text-sm">Correo</th>
                    <th class="py-3 px-4 font-semibold text-sm">Rol</th>
                    <th class="py-3 px-4 font-semibold text-sm">Fecha Registro</th>
                    <th class="py-3 px-4 font-semibold text-sm text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y">
                <!-- Data mock -->
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 flex items-center">
                        <div class="bg-blue-100 text-blue-600 rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">AD</div>
                        Admin Principal
                    </td>
                    <td class="py-3 px-4">admin@iujo.edu.ve</td>
                    <td class="py-3 px-4"><span class="bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs font-medium">Administrador</span></td>
                    <td class="py-3 px-4">2026-01-10</td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-blue-500 hover:text-blue-700 mx-1"><i class="fas fa-edit"></i></button>
                        <button class="text-red-500 hover:text-red-700 mx-1"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 flex items-center">
                        <div class="bg-green-100 text-green-600 rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">AS</div>
                        Asistente Social
                    </td>
                    <td class="py-3 px-4">asistente@iujo.edu.ve</td>
                    <td class="py-3 px-4"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium">Visualizador</span></td>
                    <td class="py-3 px-4">2026-02-15</td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-blue-500 hover:text-blue-700 mx-1"><i class="fas fa-edit"></i></button>
                        <button class="text-red-500 hover:text-red-700 mx-1"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between border-t pt-4 text-sm text-gray-500">
        <span>Mostrando 1 a 2 de 2 entradas</span>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50">Anterior</button>
            <button class="px-3 py-1 bg-primary-50 text-primary-600 border border-primary-200 rounded">1</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50">Siguiente</button>
        </div>
    </div>
</div>
