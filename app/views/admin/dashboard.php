<!-- Dashboard Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full text-blue-500 mr-4">
                <i class="fas fa-file-invoice text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Encuestas</p>
                <h3 class="text-2xl font-bold text-gray-800">1,245</h3>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full text-green-500 mr-4">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Aprobadas</p>
                <h3 class="text-2xl font-bold text-gray-800">920</h3>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full text-yellow-500 mr-4">
                <i class="fas fa-clock text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Pendientes</p>
                <h3 class="text-2xl font-bold text-gray-800">125</h3>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full text-purple-500 mr-4">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Usuarios</p>
                <h3 class="text-2xl font-bold text-gray-800">48</h3>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimas Respuestas -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Últimas Encuestas</h3>
            <a href="<?php echo BASE_URL; ?>/admin/respuestas" class="text-sm text-blue-500 hover:underline">Ver todas</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="py-2 px-4 border-b">Estudiante</th>
                        <th class="py-2 px-4 border-b">Cédula</th>
                        <th class="py-2 px-4 border-b">Fecha</th>
                        <th class="py-2 px-4 border-b">Estado</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class="py-3 px-4 border-b">Juan Pérez</td>
                        <td class="py-3 px-4 border-b">V-20123456</td>
                        <td class="py-3 px-4 border-b">2026-03-03</td>
                        <td class="py-3 px-4 border-b"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Completa</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 border-b">María Gómez</td>
                        <td class="py-3 px-4 border-b">V-25654321</td>
                        <td class="py-3 px-4 border-b">2026-03-02</td>
                        <td class="py-3 px-4 border-b"><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Pendiente</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Accesos Rápidos</h3>
        <ul class="space-y-3">
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/usuarios" class="flex items-center p-3 hover:bg-gray-50 rounded border transition">
                    <div class="p-2 bg-blue-100 text-blue-500 rounded mr-3"><i class="fas fa-user-plus"></i></div>
                    <div>
                        <p class="font-medium text-gray-800">Añadir nuevo usuario</p>
                        <p class="text-xs text-gray-500">Gestionar permisos de acceso al dashboard.</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo BASE_URL; ?>/admin/catalogos" class="flex items-center p-3 hover:bg-gray-50 rounded border transition">
                    <div class="p-2 bg-purple-100 text-purple-500 rounded mr-3"><i class="fas fa-edit"></i></div>
                    <div>
                        <p class="font-medium text-gray-800">Modificar Catálogos</p>
                        <p class="text-xs text-gray-500">Añade o edita las opciones del formulario socioeconómico.</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
