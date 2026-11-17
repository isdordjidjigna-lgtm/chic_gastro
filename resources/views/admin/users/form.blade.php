<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->exists ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur' }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-4">
                    @csrf
                    @if($user->exists) @method('PUT') @endif

                    <div>
                        <x-input-label for="name" value="Nom" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Rôle" />
                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="$user->exists ? 'Nouveau mot de passe (laisser vide pour conserver)' : 'Mot de passe'" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="actif" value="1" @checked(old('actif', $user->exists ? $user->actif : true)) class="rounded border-gray-300">
                        <span class="text-sm text-gray-700">Compte actif</span>
                    </label>

                    <div class="flex gap-3">
                        <x-primary-button>Enregistrer</x-primary-button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-600">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
