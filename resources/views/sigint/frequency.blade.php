<tbody class="divide-y divide-slate-800 text-slate-200"
    x-data="{ openRow: null }">

    <!-- MAIN ROW -->
    <tr class="hover:bg-slate-800 transition cursor-pointer"
        @click="openRow === 1 ? openRow = null : openRow = 1">

        <td class="px-4 py-3 font-medium">12 Hz</td>
        <td class="px-4 py-3">23:31 06 Jan 2014</td>
        <td class="px-4 py-3 truncate max-w-xs">
            fgdf
        </td>
        <td class="px-4 py-3">1×1</td>
        <td class="px-4 py-3">123°</td>
        <td class="px-4 py-3">—</td>
        <td class="px-4 py-3 flex gap-3">
            <span class="text-blue-400">Edit</span>
            <span class="text-red-400">Delete</span>
        </td>
    </tr>

    <!-- EXPANDED DETAILS ROW -->
    <tr x-show="openRow === 1"
        x-transition
        class="bg-slate-900">

        <td colspan="7" class="px-6 py-5">

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 text-sm">

                <!-- Conversation -->
                <div class="md:col-span-2">
                    <div class="text-slate-400 mb-1">Full Conversation</div>
                    <div class="bg-slate-950 border border-slate-800 rounded p-3 text-slate-200">
                        fgdf
                    </div>
                </div>

                <!-- Metadata -->
                <div>
                    <div class="text-slate-400 mb-1">Signal Details</div>
                    <ul class="space-y-1 text-slate-300">
                        <li><strong>Frequency:</strong> 12 Hz</li>
                        <li><strong>Clarity:</strong> 1×1</li>
                        <li><strong>LOB:</strong> 123°</li>
                        <li><strong>Origin:</strong> Unknown</li>
                        <li><strong>Date:</strong> 06 Jan 2014</li>
                        <li><strong>Time:</strong> 23:31</li>
                    </ul>
                </div>

                <!-- Quick Actions -->
                <div>
                    <div class="text-slate-400 mb-1">Actions</div>
                    <div class="flex flex-col gap-2">
                        <button class="bg-blue-600 hover:bg-blue-500 px-3 py-2 rounded text-xs">
                            Flag for Review
                        </button>
                        <button class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded text-xs">
                            Add Note
                        </button>
                        <button class="bg-red-600 hover:bg-red-500 px-3 py-2 rounded text-xs">
                            Mark as Suspicious
                        </button>
                    </div>
                </div>

            </div>

        </td>
    </tr>

</tbody>