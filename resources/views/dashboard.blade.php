<x-app-layout>

    <div class="space-y-8">

        <!-- PAGE HEADER -->
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-400">
                System overview and operational status
            </p>
        </div>

        <!-- STATUS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h3 class="text-xs uppercase tracking-wider text-slate-400">
                    Total Records
                </h3>
                <p class="text-3xl font-bold text-white mt-2">128</p>
                <span class="text-xs text-slate-500">All modules</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h3 class="text-xs uppercase tracking-wider text-slate-400">
                    Active SIGINT
                </h3>
                <p class="text-3xl font-bold text-white mt-2">42</p>
                <span class="text-xs text-slate-500">Current frequencies</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h3 class="text-xs uppercase tracking-wider text-slate-400">
                    Pending Reviews
                </h3>
                <p class="text-3xl font-bold text-white mt-2">7</p>
                <span class="text-xs text-slate-500">Awaiting validation</span>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-lg p-5">
                <h3 class="text-xs uppercase tracking-wider text-slate-400">
                    Alerts
                </h3>
                <p class="text-3xl font-bold text-red-400 mt-2">3</p>
                <span class="text-xs text-slate-500">Immediate attention</span>
            </div>

        </div>

        <!-- ACTIVITY + ALERTS -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <!-- RECENT ACTIVITY -->
            <div class="xl:col-span-2 bg-slate-900 border border-slate-800 rounded-lg">
                <div class="p-5 border-b border-slate-800">
                    <h2 class="font-semibold text-white">Recent Activity</h2>
                </div>
                <div class="p-5 overflow-x-auto">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-slate-800 text-slate-400 uppercase text-xs tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Time</th>
                                <th class="px-4 py-3 text-left">Module</th>
                                <th class="px-4 py-3 text-left">Action</th>
                                <th class="px-4 py-3 text-left">User</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800 text-slate-200">
                            <tr class="hover:bg-slate-800 transition">
                                <td class="px-4 py-3">08:41</td>
                                <td class="px-4 py-3 font-medium">SIGINT</td>
                                <td class="px-4 py-3">New frequency logged</td>
                                <td class="px-4 py-3">F. Malgath</td>
                            </tr>
                            <tr class="hover:bg-slate-800 transition">
                                <td class="px-4 py-3">07:15</td>
                                <td class="px-4 py-3 font-medium">D-FORENSICS</td>
                                <td class="px-4 py-3">File uploaded</td>
                                <td class="px-4 py-3">Admin</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ALERTS PANEL -->
            <div class="bg-slate-900 border border-slate-800 rounded-lg">
                <div class="p-5 border-b border-slate-800">
                    <h2 class="font-semibold text-white">Alerts</h2>
                </div>
                <div class="p-5 space-y-4 text-sm">
                    <div class="border-l-4 border-red-600 pl-3">
                        <div class="text-red-400 font-semibold">High Priority</div>
                        <div class="text-slate-300">Unverified SIGINT entry detected</div>
                    </div>
                    <div class="border-l-4 border-yellow-500 pl-3">
                        <div class="text-yellow-400 font-semibold">Pending Review</div>
                        <div class="text-slate-300">GEOINT data awaiting validation</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODULE OVERVIEW -->
        <div class="bg-slate-900 border border-slate-800 rounded-lg">
            <div class="p-5 border-b border-slate-800">
                <h2 class="font-semibold text-white">Module Overview</h2>
            </div>
            <div class="p-5 overflow-x-auto">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-slate-800 text-slate-400 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">Module</th>
                            <th class="px-4 py-3 text-left">Records</th>
                            <th class="px-4 py-3 text-left">Last Update</th>
                            <th class="px-4 py-3 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-slate-200">
                        <tr class="hover:bg-slate-800 transition">
                            <td class="px-4 py-3 font-medium">SIGINT</td>
                            <td class="px-4 py-3">42</td>
                            <td class="px-4 py-3">Today</td>
                            <td class="px-4 py-3 text-green-400 font-semibold">Active</td>
                        </tr>
                        <tr class="hover:bg-slate-800 transition">
                            <td class="px-4 py-3 font-medium">GEOINT</td>
                            <td class="px-4 py-3">18</td>
                            <td class="px-4 py-3">Yesterday</td>
                            <td class="px-4 py-3 text-yellow-400 font-semibold">Pending</td>
                        </tr>
                        <tr class="hover:bg-slate-800 transition">
                            <td class="px-4 py-3 font-medium">D-FORENSICS</td>
                            <td class="px-4 py-3">11</td>
                            <td class="px-4 py-3">2 days ago</td>
                            <td class="px-4 py-3 text-blue-400 font-semibold">Stable</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-app-layout>