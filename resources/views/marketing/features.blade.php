<section id="features" class="relative pt-20 overflow-hidden bg-blue-600 pb-28 sm:py-32" aria-label="Features for running your farm">
    <div class="relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-2xl md:mx-auto md:text-center xl:max-w-none">
                <h2 class="text-3xl tracking-tight text-white font-display sm:text-4xl md:text-5xl">Everything you need to run your printer farm.</h2>
                <p class="mt-6 text-lg tracking-tight text-blue-100">Well everything you need if you aren't that picky about minor details like tax compliance.</p>
            </div>
        </div>
        <div x-data x-tabs class="grid items-center grid-cols-1 pt-10 mt-16 gap-y-2 sm:gap-y-6 md:mt-20 lg:grid-cols-12 lg:pt-0">
            <div x-tabs:list class="flex pb-4 -mx-4 overflow-x-auto sm:mx-0 sm:overflow-visible sm:pb-0 lg:col-span-5">
                <div class="relative z-10 flex px-4 gap-x-4 whitespace-nowrap sm:mx-auto sm:px-0 lg:mx-0 lg:block lg:gap-x-0 lg:gap-y-1 lg:whitespace-normal">
                    @foreach($features as $feature)
                    <div x-tabs:tab :class="$tab.isSelected ? 'bg-white lg:bg-white/10 lg:ring-1 lg:ring-inset lg:ring-white/10' : 'hover:bg-white/10 lg:hover:bg-white/5'"
                        class="relative px-4 py-1 rounded-full group lg:rounded-r-none lg:rounded-l-xl lg:p-6"
                    >
                        <h3>
                            <button :class="$tab.isSelected ? 'text-blue-600' : 'text-blue-100 hover:text-white'"
                                class="font-display text-lg [&:not(:focus-visible)]:focus:outline-none lg:text-white" id="headlessui-tabs-tab-:R2ja9m:" role="tab" type="button" aria-selected="false" tabindex="-1" aria-controls="headlessui-tabs-panel-:Rla9m:"
                            >
                                <span class="absolute inset-0 rounded-full lg:rounded-r-none lg:rounded-l-xl"></span>{{ $feature['label'] }}
                            </button>
                        </h3>
                        <p :class="$tab.isSelected ? 'text-white' : 'text-blue-100 group-hover:text-white'" class="hidden mt-2 text-sm lg:block">{{ $feature['description'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            <div x-tabs:panels class="lg:col-span-7">
                @foreach($features as $feature)
                <div x-tabs:panel id="headlessui-tabs-panel-:R15a9m:" role="tabpanel" tabindex="0" aria-labelledby="headlessui-tabs-tab-:R33a9m:">
                    <div class="relative sm:px-6 lg:hidden">
                        <div class="absolute -inset-x-4 top-[-6.5rem] bottom-[-4.25rem] bg-white/10 ring-1 ring-inset ring-white/10 sm:inset-x-0 sm:rounded-t-xl"></div>
                        <p class="relative max-w-2xl mx-auto text-base text-white sm:text-center">{{ $feature['description'] }}</p>
                    </div>
                    <div class="mt-10 w-[45rem] overflow-hidden rounded-xl bg-slate-50 shadow-xl shadow-blue-900/20 sm:w-auto lg:mt-0 lg:w-[67.8125rem]">
                        <img alt="" src="{{ $feature['image'] }}" class="w-full" style="color:transparent">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<section id="secondary-features" class="bg-white">
    <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:py-24 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">All-in-one platform</h2>
            <p class="mt-4 text-lg text-gray-500">Ac euismod vel sit maecenas id pellentesque eu sed consectetur. Malesuada adipiscing sagittis vel nulla nec.</p>
        </div>
        <dl class="mt-12 space-y-10 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-12 sm:space-y-0 lg:grid-cols-4 lg:gap-x-8">
            @foreach ($secondary as $feature)
            <div class="relative">
                <dt>
                    <!-- Heroicon name: outline/check -->
                    <svg class="absolute w-6 h-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <p class="text-lg font-medium leading-6 text-gray-900 ml-9">{{ $feature['label'] }}</p>
                </dt>
                <dd class="mt-2 text-base text-gray-500 ml-9">{{ $feature['description'] }}</dd>
            </div>
            @endforeach
        </dl>
    </div>
</section>
