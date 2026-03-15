<!-- Responses management -->
<div class="bg-white rounded-lg shadow-sm border p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800">Respuestas Recibidas</h3>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-sm text-sm font-medium transition">
            <i class="fas fa-file-excel mr-2"></i> Exportar
        </button>
    </div>

    <!-- Search/Filter -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" placeholder="Buscar por nombre o cédula..." class="border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500 outline-none md:col-span-2">
        <select class="border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
            <option value="">Todas las carreras</option>
            <option value="1">Informática</option>
            <option value="2">Administración</option>
            <option value="3">Educación</option>
        </select>
        <select class="border border-gray-300 rounded-md p-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
            <option value="">Estado</option>
            <option value="completa">Completa</option>
            <option value="incompleta">Pendiente</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-y">
                    <th class="py-3 px-4 font-semibold text-sm">ID</th>
                    <th class="py-3 px-4 font-semibold text-sm">Estudiante</th>
                    <th class="py-3 px-4 font-semibold text-sm">Cédula</th>
                    <th class="py-3 px-4 font-semibold text-sm">Carrera</th>
                    <th class="py-3 px-4 font-semibold text-sm">Fecha</th>
                    <th class="py-3 px-4 font-semibold text-sm">Estrato</th>
                    <th class="py-3 px-4 font-semibold text-sm text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y">
                <?php if (isset($encuestas)): ?>
                    <!-- Data mock -->
                    <?php foreach ($encuestas['items'] as $encuesta): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">#<?= $encuesta['id']?></td>
                            <td class="py-3 px-4 font-medium text-gray-800"><?= $encuesta['estudiante'] ?></td>
                            <td class="py-3 px-4"><?= $encuesta['cedula'] ?></td>
                            <td class="py-3 px-4 text-gray-500"><?= $encuesta['carrera'] ?></td>
                            <td class="py-3 px-4"><?= $encuesta['creado'] ?></td>
                            <td class="py-3 px-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium border border-green-200"><?= $encuesta['estrato'] ?></span></td>
                            <td class="py-3 px-4 text-right">
                                <button class="text-indigo-500 hover:text-indigo-700 mx-1" title="Ver Detalles"><i class="fas fa-eye"></i></button>
                                <button class="text-blue-500 hover:text-blue-700 mx-1" title="Editar"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 flex items-center justify-between border-t pt-4 text-sm text-gray-500">
        <span>Mostrando 1 a 3 de 1,245 entradas</span>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50" disabled>Anterior</button>
            <button class="px-3 py-1 bg-primary-50 text-primary-600 border border-primary-200 rounded">1</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border rounded hover:bg-gray-50">3</button>
            <span class="px-2 py-1">...</span>
            <button class="px-3 py-1 border rounded hover:bg-gray-50">Siguiente</button>
        </div>
    </div>
</div>
