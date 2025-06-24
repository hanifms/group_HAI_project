<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('UI Style Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Colors -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Colors</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <!-- Primary Colors -->
                    <div>
                        <div class="h-16 rounded-lg bg-primary-50 mb-1"></div>
                        <div class="text-xs text-gray-600">primary-50</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-primary-200 mb-1"></div>
                        <div class="text-xs text-gray-600">primary-200</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-primary-500 mb-1"></div>
                        <div class="text-xs text-gray-600">primary-500</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-primary-700 mb-1"></div>
                        <div class="text-xs text-gray-600">primary-700</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-primary-900 mb-1"></div>
                        <div class="text-xs text-gray-600">primary-900</div>
                    </div>

                    <!-- Secondary Colors -->
                    <div>
                        <div class="h-16 rounded-lg bg-secondary-50 mb-1"></div>
                        <div class="text-xs text-gray-600">secondary-50</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-secondary-200 mb-1"></div>
                        <div class="text-xs text-gray-600">secondary-200</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-secondary-500 mb-1"></div>
                        <div class="text-xs text-gray-600">secondary-500</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-secondary-700 mb-1"></div>
                        <div class="text-xs text-gray-600">secondary-700</div>
                    </div>
                    <div>
                        <div class="h-16 rounded-lg bg-secondary-900 mb-1"></div>
                        <div class="text-xs text-gray-600">secondary-900</div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Buttons</h3>
                <div class="space-y-4">
                    <div class="flex flex-wrap gap-4">
                        <button class="btn-primary">Primary Button</button>
                        <button class="btn-secondary">Secondary Button</button>
                        <button class="btn-outline">Outline Button</button>
                        <button class="btn-ghost">Ghost Button</button>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Usage Examples:</p>
                        <code class="text-sm bg-gray-200 px-2 py-1 rounded">
                            &lt;button class="btn-primary"&gt;Primary Button&lt;/button&gt;
                        </code>
                    </div>
                </div>
            </div>

            <!-- Cards -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Cards</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="card">
                        <h3 class="text-lg font-semibold mb-2">Regular Card</h3>
                        <p class="text-gray-600">This is a basic card component used throughout the application.</p>
                    </div>
                    <div class="card-highlight">
                        <h3 class="text-lg font-semibold mb-2">Highlight Card</h3>
                        <p class="text-gray-600">This card includes a colored accent border for added emphasis.</p>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg mt-4">
                    <p class="text-sm text-gray-600 mb-2">Usage Examples:</p>
                    <code class="text-sm bg-gray-200 px-2 py-1 rounded">
                        &lt;div class="card"&gt;...content...&lt;/div&gt;
                    </code>
                </div>
            </div>

            <!-- Badges -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Badges</h3>
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-primary">Primary</span>
                    <span class="badge badge-secondary">Secondary</span>
                    <span class="badge badge-success">Success</span>
                    <span class="badge badge-warning">Warning</span>
                    <span class="badge badge-danger">Danger</span>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg mt-4">
                    <p class="text-sm text-gray-600 mb-2">Usage Examples:</p>
                    <code class="text-sm bg-gray-200 px-2 py-1 rounded">
                        &lt;span class="badge badge-primary"&gt;Primary&lt;/span&gt;
                    </code>
                </div>
            </div>

            <!-- Form Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Form Controls</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-input" placeholder="you@example.com">
                        <p class="form-error">Please enter a valid email address.</p>
                    </div>
                    <div>
                        <label for="dropdown" class="form-label">Select an option</label>
                        <select id="dropdown" class="form-input">
                            <option>Option 1</option>
                            <option>Option 2</option>
                            <option>Option 3</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-100 p-4 rounded-lg mt-4">
                    <p class="text-sm text-gray-600 mb-2">Usage Examples:</p>
                    <code class="text-sm bg-gray-200 px-2 py-1 rounded">
                        &lt;label for="email" class="form-label"&gt;Email&lt;/label&gt;<br>
                        &lt;input type="email" id="email" class="form-input"&gt;<br>
                        &lt;p class="form-error"&gt;Error message&lt;/p&gt;
                    </code>
                </div>
            </div>

            <!-- Typography -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4 text-primary-900">Typography</h3>
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-bold text-primary-900 mb-1">Heading 1</h1>
                        <p class="text-sm text-gray-600">text-3xl font-bold text-primary-900</p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-semibold text-primary-900 mb-1">Heading 2</h2>
                        <p class="text-sm text-gray-600">text-2xl font-semibold text-primary-900</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-primary-800 mb-1">Heading 3</h3>
                        <p class="text-sm text-gray-600">text-xl font-semibold text-primary-800</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-primary-700 mb-1">Heading 4</h4>
                        <p class="text-sm text-gray-600">text-lg font-medium text-primary-700</p>
                    </div>
                    <div>
                        <p class="mb-1">Regular paragraph text</p>
                        <p class="text-sm text-gray-600">Default paragraph styling</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Small text</p>
                        <p class="text-sm text-gray-600">text-sm text-gray-600</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
