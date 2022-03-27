@props(['completed', 'team'])

<div class="mb-6 overflow-hidden bg-white rounded-md shadow-xl dark:shadow dark:border dark:border-gray-700 dark:bg-gray-800">
    <div class="p-6 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:px-20">
        <div>
            <x-jet-application-logo class="block w-auto h-12 text-gray-900 dark:text-gray-200" />
        </div>

        <div class="mt-6 text-gray-500 dark:text-gray-400">
            OctoQueue provides a beautiful and robust management for all of your OctoPrint printers. We hope you love it.
        </div>
    </div>

    <div class="grid grid-cols-1 bg-gray-200 dark:bg-gray-800 md:grid-cols-2">
        <div class="p-6">
            <div class="flex items-center">
                <x-icon-printer class="w-8 h-8 text-gray-400"/>
                <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300"><a href="/printers">Printers</a></div>
            </div>

            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Laravel has wonderful documentation covering every aspect of the framework. Whether you're new to the framework or have previous experience, we recommend reading all of the documentation from beginning to end.
                </div>

                <a href="/printers/create">
                    <div class="flex items-center mt-3 text-sm font-semibold text-blue-700 dark:text-blue-400">
                        <div>{{ $completed['has_printers'] ? 'Add another printer' : 'Create your first printer' }}</div>

                        <div class="ml-1 text-blue-500 dark:text-blue-400">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-t-0 md:border-l">
            <div class="flex items-center">
                <x-icon-filament class="w-8 h-8 text-gray-400"/>
                <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300"><a href="/spools">Spools</a></div>
            </div>

            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    OctoQueue uses spools to help select the next print that should be printed. OctoPrint also uses the weight of the spool to calculate if a print can be printed with the remaining filament.
                </div>

                <a href="/spools/create">
                    <div class="flex items-center mt-3 text-sm font-semibold text-blue-700 dark:text-blue-400">
                        <div>{{ $completed['has_spools'] ? 'Add another spool' : 'Create your first spool' }}</div>

                        <div class="ml-1 text-blue-500 dark:text-blue-400">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <x-icon-box class="w-8 h-8 text-gray-400"/>
                <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300"><a href="/job-types">Job Types</a></div>
            </div>

            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    OctoQueue uses job types to help select the next print that should be printed. Each type has a name and a priority.
                </div>

                <a href="/teams/{{ $team->id }}#job-types">
                    <div class="flex items-center mt-3 text-sm font-semibold text-blue-700 dark:text-blue-400">
                        <div>{{ $completed['has_types'] ? 'View job types' : 'Create your first job' }}</div>

                        <div class="ml-1 text-blue-500 dark:text-blue-400">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-700 md:border-l">
            <div class="flex items-center">
                <x-icon-hotend class="w-8 h-8 text-gray-400"/>
                <div class="ml-4 text-lg font-semibold leading-7 text-gray-600 dark:text-gray-300"><a href="/queue">Print Queue</a></div>
            </div>

            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    The print queue is a list of jobs that need to be printed, each job can have a color, type, and selected files for one or all of the printers on the team.
                </div>

                <a href="/jobs/create">
                    <div class="flex items-center mt-3 text-sm font-semibold text-blue-700 dark:text-blue-400">
                        <div>{{ $completed['has_jobs'] ? 'Add more jobs' : 'Add a job to the queue' }}</div>

                        <div class="ml-1 text-blue-500 dark:text-blue-400">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
