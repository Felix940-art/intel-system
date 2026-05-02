<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">📋 Audit Logs</h1>

        <!-- FILTERS -->
        <form method="GET" class="flex gap-3 mb-4">

            <select name="module" class="bg-slate-800 border border-slate-700 rounded px-3 py-2">
                <option value="">All Modules</option>
                <option value="SRE">SRE</option>
                <option value="FREQUENCY">Frequency</option>
                <option value="GEOINT">GEOINT</option>
                <option value="FORENSICS">Forensics</option>
            </select>

            <select name="action" class="bg-slate-800 border border-slate-700 rounded px-3 py-2">
                <option value="">All Actions</option>
                <option value="CREATE">Create</option>
                <option value="UPDATE">Update</option>
                <option value="DELETE">Delete</option>
            </select>

            <button class="bg-blue-600 px-4 py-2 rounded">Filter</button>
        </form>

        <!-- TABLE -->
        <div class="bg-slate-900 rounded-xl overflow-hidden border border-slate-700">

            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-slate-400">
                    <tr>
                        <th class="p-3 text-left">User</th>
                        <th class="p-3">Action</th>
                        <th class="p-3">Module</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3">IP</th>
                        <th class="p-3">Time</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($logs as $log)
                    <tr class="border-t border-slate-800 hover:bg-slate-800/50">

                        <td class="p-3">
                            {{ $log->user->name ?? 'System' }}
                        </td>

                        <td class="p-3">
                            <span class="
                            px-2 py-1 rounded text-xs
                            @if($log->action === 'CREATE') bg-green-500/20 text-green-400
                            @elseif($log->action === 'UPDATE') bg-yellow-500/20 text-yellow-400
                            @else bg-red-500/20 text-red-400
                            @endif
                        ">
                                {{ $log->action }}
                            </span>
                        </td>

                        <td class="p-3">{{ $log->module }}</td>

                        <td class="p-3">{{ $log->description }}</td>

                        <td class="p-3">{{ $log->ip }}</td>

                        <td class="p-3">
                            {{ $log->created_at->format('M d, Y H:i') }}
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- PAGINATION -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

</x-app-layout>