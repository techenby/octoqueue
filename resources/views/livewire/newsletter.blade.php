<form wire:submit.prevent="submit" class="sm:flex sm:w-full sm:max-w-lg">
    <div class="flex-1 min-w-0">
        <label for="hero-email" class="sr-only">Email address</label>
        <input id="hero-email" wire:model="email" name="email" type="email" class="block w-full px-5 py-3 text-base text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm dark:text-gray-200 dark:placeholder-gray-400 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter your email">
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-3">
        <button type="submit" class="block w-full px-5 py-3 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:px-10">Notify me</button>
    </div>
</form>
