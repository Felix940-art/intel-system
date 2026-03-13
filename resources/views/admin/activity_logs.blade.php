<x-app-layout>

    <div class="p-6">

        <h1 class="text-xl text-white mb-4">
            System Activity Logs
        </h1>

        <table class="w-full text-sm text-left bg-slate-900">

            <thead class="bg-slate-800 text-slate-300">
                <tr>
                    <th class="p-3">User</th>
                    <th class="p-3">Module</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Description</th>
                    <th class="p-3">IP</th>
                    <th class="p-3">Time</th>
                </tr>
            </thead>

            <tbody>

                @foreach($logs as $log)

                <tr class="border-t border-slate-800">

                    <td class="p-3">{{ $log->user->name }}</td>
                    <td class="p-3">{{ strtoupper($log->module) }}</td>
                    <td class="p-3">{{ $log->action }}</td>
                    <td class="p-3">{{ $log->description }}</td>
                    <td class="p-3">{{ $log->ip_address }}</td>
                    <td class="p-3">{{ $log->created_at }}</td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</x-app-layout>